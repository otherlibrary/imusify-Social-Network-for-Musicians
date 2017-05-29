import {Component, Input, NgZone, OnInit} from '@angular/core';
import {PlayerService} from "../player.service";
import {IRecord} from "../../interfases/IRecord";
import {IRecordEvent} from "../../interfases/IRecordEvent";
declare const WaveSurfer: any;


@Component({
  selector: 'app-player',
  templateUrl: './player.component.html',
  styleUrls: ['./player.component.scss']
})

/**
 * Input Subject: playInputSubject
 * @param Object: type: string, record: IRecord
 *  type: play | pause
 *
 *  Input Subject: playerOutputSubject
 *  @param Object: type: string, record: IRecord
 *  type: play | pause | ready | finish
 */
export class PlayerComponent implements OnInit {
  public records: IRecord[];
  public isReady: boolean;
  public currentTrack: IRecord;
  public lastPlayedTrack: IRecord;
  public isPlay: boolean;
  public streamTrack: any;
  public currentTime: number;
  public durationTime: number;
  public isMute: boolean;
  public isTrackRepeated: boolean;
  public isShuffleOn: boolean;
  public currentVol: number;
  private _cacheVolume: number;

  public isBig: boolean = false;
  public isQueue: boolean;

  @Input() public autoPlay: boolean;

  constructor(private _playerService: PlayerService, public zone: NgZone) {
  }

  ngOnInit() {
    console.log('player component init');
    this._initWaveSurfer();
    this.getCurrentPlayList();
  }

  getCurrentPlayList() {
    this._playerService.getCurrentPlaylist().subscribe((data) => {
      this.records = data.records.filter((record: IRecord) => {
        if(record.is_track) {
          return record;
        }
      });
      if(this.records.length > 0) {
        this.lastPlayedTrack = this.records[0];
        this.setCurrentPlayedTrack(this.lastPlayedTrack);
      } else {
        console.log('empty records');
      }
    }, err => console.error(err));
  }

  /**
   * Events: ready, play, finish
   * @private
   */
  private _initWaveSurfer(): void {
    this._playerService.wavesurfer = WaveSurfer.create({
      container: '#waveform',
      backend: 'MediaElement',
      height: 70,
      progressColor: '#c23a48',
      cursorColor: '#fff',
      barWidth: 2
    });

    const wavesurfer = this._playerService.wavesurfer;
    this.currentVol = wavesurfer.getVolume() || 1;

    wavesurfer.on('ready', () => {
      console.log('ready');
      this.isReady = true;
      if(this.autoPlay) {
        this._playerService.wavesurfer.play();
      }
      this._playerService.playerOutputSubject.next({type: 'ready', record: this.currentTrack});
    });

    wavesurfer.on('play', () => {
      console.log('play');
      wavesurfer.setVolume(this.currentVol);
      this._playerService.playerOutputSubject.next({type: 'play', record: this.currentTrack});
      this.isPlay = this.isPlaying();
    });

    wavesurfer.on('pause', () => {
      console.log('pause');
      this._playerService.playerOutputSubject.next({type: 'pause', record: this.currentTrack});
      this.isPlay = this.isPlaying();
    });

    wavesurfer.on('finish', () => {
      if(this.isTrackRepeated) {
        this.repeatTrack();
      } else if(this.isShuffleOn) {
        this.shuffleTracks();
      } else {
        this.playNextTrack();
      }
      this._playerService.playerOutputSubject.next({type: 'finish', record: this.currentTrack});
    });

    wavesurfer.on('audioprocess', () => {
      this.zone.run(() => {
        this.currentTime = this.isReady ? wavesurfer.getCurrentTime() : 0;
      });
    });

    this._playerService.playInputSubject.subscribe((res: IRecordEvent) => {
      console.log(res);
      if(res.type === 'pause') {
        this.pauseTrack();
      }
      if(res.type === 'play') {
        if(res.record.id === this.currentTrack.id) {
          this.playTrack();
        } else {
          this.autoPlay = true;
          this.setCurrentPlayedTrack(res.record);
        }
      }
    });
  }

  /**
   * set current track played
   * @param record
   */
  public setCurrentPlayedTrack(record: IRecord): void {
    this.isReady = false;
    this.currentTrack = record;
    this._playerService.getTrackLink(record.trackLink).subscribe(track => {
      this.streamTrack = track.stream_url + '?nor=1';
      this._playerService.wavesurfer
        .load(this.streamTrack, JSON.parse(this.currentTrack.waveform));
      this.durationTime = track.duration;
    });
  }

  /**
   * get status track
   * @returns {boolean}
   */
  public isPlaying(): boolean {
    return this._playerService.wavesurfer.isPlaying();
  }

  public playTrack(): void {
    this._playerService.wavesurfer.play();
  }

  public pauseTrack() {
    this._playerService.wavesurfer.pause();
  }

  public stopTrack(): void {
    this._playerService.wavesurfer.stop();
  }

  playNextTrack() {
    this.autoPlay = true;
    this.isShuffleOn ? this.shuffleTracks() : this.setCurrentPlayedTrack(this.getNextTrack());
  }

  playPreviousTrack() {
    this.autoPlay = true;
    this.setCurrentPlayedTrack(this.getPreviousTrack());
  }

  toggleRepeat() {
    this.isTrackRepeated = !this.isTrackRepeated
  }

  repeatTrack() {
    let index = this.getCurrentAudioTrackIndex();
    this.setCurrentPlayedTrack(this.records[index]);
  }

  shuffleTracks() {
    let randomTrack = this.records[Math.floor(Math.random()*this.records.length)];
    this.setCurrentPlayedTrack(randomTrack);
  }

  toggleShuffle() {
    this.isShuffleOn = !this.isShuffleOn;
  }

  toggleVolume() {
    this._playerService.wavesurfer.toggleMute();
    this.isMute = this._playerService.wavesurfer.getMute();
    if(this.currentVol != 0) {
      this._cacheVolume = this.currentVol;
      this.currentVol = 0;
    } else {
      this.currentVol = this._cacheVolume;
    }

  }

  /**
   * change volume
   * @param e
   */
  changeVolume(e) {
    let val = e.target.value;
    this.currentVol = val;
    this._playerService.wavesurfer.setVolume(val);
    if(val <= 0) {
      this.isMute = !this.isMute;
    } else if(val > 0){
      this.isMute = false;
    }
  }

  /**
   * return next track
   * @returns {IRecord}
   */
  getNextTrack(): IRecord {
    const currentTrackIndex = this.getCurrentAudioTrackIndex();
    if (currentTrackIndex == this.records.length - 1) {
      return this.records[0];
    }
    return this.records[currentTrackIndex + 1];
  }

  /**
   * return previous track
   * @returns {IRecord}
   */
  getPreviousTrack(): IRecord {
    const currentTrackIndex = this.getCurrentAudioTrackIndex();
    if (+currentTrackIndex === 0) {
      return this.records[this.records.length - 1];
    }
    return this.records[currentTrackIndex - 1];
  }

  /**
   * get audio track by id
   * @param id
   * @returns {undefined|IRecord}
   */
  getAudioTrackById(id: string) {
    return this.records.find((elem) => <string>elem.id === id);
  }

  /**
   * get current track index
   * @returns {number}
   */
  getCurrentAudioTrackIndex() {
    let id = (this.currentTrack.id).toString();
    return this.records.indexOf(this.getAudioTrackById(id));
  }


  //TODO(AlexSol): move to directive
  togglePlayer() {
    this.isBig = !this.isBig;
  }

  toggleQueue() {
    this.isQueue = !this.isQueue;
  }
}
