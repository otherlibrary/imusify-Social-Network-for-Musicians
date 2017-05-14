import {Component, NgZone, OnInit} from '@angular/core';
import {PlayerService} from "../player.service";
import {IRecord} from "../../interfases/IRecord";
declare const WaveSurfer: any;

const peaks = [0.013611255213618279, -0.00634765625, 0.01745658740401268, -0.031005859375, 0.0238349549472332, -0.0263671875, 0.024994660168886185];
@Component({
  selector: 'app-player',
  templateUrl: './player.component.html',
  styleUrls: ['./player.component.scss']
})
export class PlayerComponent implements OnInit {
  public records: IRecord[];
  public wavesurfer: any;
  public isReady: boolean;
  public autoPlay: boolean;
  public currentTrack: IRecord;
  public lastPlayedTrack: IRecord;
  public isPlay: boolean;
  public streamTrack: any;
  public currentTime: number;
  public durationTime: number;

  constructor(private _playerService: PlayerService, public zone: NgZone) {
  }

  ngOnInit() {
    console.warn('player component init');
    this._initWaveSurfer();
    this.getCurrentPlayList();
  }

  getCurrentPlayList() {
    this._playerService.getCurrentPlaylist().subscribe(data => {
      this.records = data.records;
      if(this.records.length > 0) {
        this.lastPlayedTrack = this.records[0];
        this.setCurrentPlayedTrack(this.lastPlayedTrack);
      } else {
        console.warn('empty records');
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
      barWidth: 1.5
    });

    const wavesurfer = this._playerService.wavesurfer;

    wavesurfer.on('ready', () => {
      console.warn('ready');
      this.isReady = true;
      if(this.autoPlay) {
        this._playerService.wavesurfer.play();
      }
      this._playerService.playerEventSubject.next({type: 'ready', data: this.currentTrack});
    });

    wavesurfer.on('play', () => {
      console.warn('play');
      this.isPlay = this.isPlaying();
      this._playerService.playerEventSubject.next({type: 'play', data: this.currentTrack});
    });

    wavesurfer.on('pause', () => {
      console.warn('pause');
      this.isPlay = this.isPlaying();
      this._playerService.playerEventSubject.next({type: 'pause', data: this.currentTrack});
    });

    wavesurfer.on('finish', () => {
      console.warn('finish');
      this._playerService.playerEventSubject.next({type: 'finish', data: this.currentTrack});
    });

    wavesurfer.on('audioprocess', () => {
      this.zone.run(() => {
        this.currentTime = this.isReady ? wavesurfer.getCurrentTime() : 0;
      });
    });

    this._playerService.playerSubject.subscribe((record: IRecord) => {
      this.autoPlay = true;
      this.setCurrentPlayedTrack(record);
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
      this._playerService.wavesurfer.load(this.streamTrack, peaks, 'auto');
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
    console.log('play');
    this._playerService.wavesurfer.play();
  }

  public pauseTrack() {
    console.log('pause');
    this._playerService.wavesurfer.pause();
  }

  public stopTrack(): void {
    this._playerService.wavesurfer.stop();
  }

  playNextTrack() {
    console.log(this.getNextTrack());
    this.setCurrentPlayedTrack(this.getNextTrack());
  }

  playPreviousTrack() {
    this.setCurrentPlayedTrack(this.getPreviousTrack());
  }

  getNextTrack(): IRecord {
    const currentTrackIndex = this.getCurrentAudioTrackIndex();
    if (currentTrackIndex == this.records.length - 1) {
      return this.records[0];
    }
    return this.records[currentTrackIndex + 1];
  }

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
}
