import {Component, OnInit, NgZone, OnDestroy} from '@angular/core';
import {SharedService} from '../shared.service';
import {IRecord} from "../../interfases";
import * as _ from 'lodash';
import {Subscription} from "rxjs/Subscription";
declare const WaveSurfer: any;

@Component({
  selector: 'app-audio-player',
  templateUrl: './audio-player.component.html',
  styleUrls: ['./audio-player.component.scss']
})


export class AudioPlayerComponent implements OnInit, OnDestroy {
  public isShuffleOn: boolean;
  public isTrackRepeated: boolean;
  public isReady: boolean = false;
  public isBig: boolean = false;
  public isMute: boolean;
  public isQueue: boolean;
  public currentVol: number;
  private _casheVolume: number;
  public isRecordPlayed: boolean;
  public records: IRecord[];
  public currentPlayedTrack: any = null;
  public wavesurfer: any = null;
  public streamTrack: string = null;
  public currentTime: number;
  public durationTime: number;
  private initialize: any;
  private subscriberPlaySubject: Subscription;
  private subscriberPauseSubject: Subscription;

  constructor(
    private _sharedService: SharedService,
    public zone: NgZone
  ) {}

  ngOnInit() {
    this._sharedService.setPlaylistSubject.subscribe((playlist: IRecord[]) => {
      console.log('playlist', playlist);
      this.records = playlist;
    });

    this.getCurrentPlayList();

    this.initialize = _.once(() => {
      console.log(this.wavesurfer);
      this.wavesurfer.on('ready', () => {
        this.playTrack();
      });
    });
    /**
     * play track global scope
     * @type {Subscription}
     */
    this.subscriberPlaySubject = this._sharedService.playTrackSubject.subscribe((track: IRecord) => {
      if(this.currentPlayedTrack) {
        if(track.id === this.currentPlayedTrack.id) {
          this.playTrack();
        } else {
          this.setCurrentPlayedTrack(track);
          this.initialize();
        }
      } else {
        this.setCurrentPlayedTrack(track);
      }
    });

    this.subscriberPauseSubject = this._sharedService.pauseTrackSubject.subscribe((track: IRecord) => {
      if(track.id === this.currentPlayedTrack.id) {
        this.pauseTrack();
      }
    });
  }

  ngOnDestroy() {
    this.subscriberPlaySubject.unsubscribe();
    this.subscriberPauseSubject.unsubscribe();
  }

  /**
   * init wave plugin and add listeners
   */
  private initWavesurfer() {
    if (!this.wavesurfer) {
      this.wavesurfer = WaveSurfer.create({
        container: '#waveform',
        backend: 'MediaElement',
        height: 70,
        progressColor: '#c23a48',
        cursorColor: '#fff',
        barWidth: 1.5,
        renderer: 'MultiCanvas'
      });
      let waveform = JSON.parse(this.currentPlayedTrack.waveform);
      this.wavesurfer.load(this.streamTrack, waveform);

      this.wavesurfer.on('audioprocess', () => {
        this.zone.run(() => {
          this.currentTime = this.isReady ? this.wavesurfer.getCurrentTime() : 0;
        });
      });

      this.wavesurfer.on('finish', () => {
        if(this.isTrackRepeated) {
            this.repeatTrack();
        } else if(this.isShuffleOn) {
            this.shuffleTracks();
        } else {
          this.playNextTrack();
        }
      });

      this.currentVol = this.wavesurfer.getVolume();
    } else {
      this.initialize();
      let waveform = JSON.parse(this.currentPlayedTrack.waveform);
      this.wavesurfer.load(this.streamTrack, waveform);
    }
  }

  /**
   * get playlist page music
   */
  getCurrentPlayList() {
    this._sharedService.getMusic().subscribe(data => {
      this.records = data.records;
      if(this.records.length > 0) {
        this.setCurrentPlayedTrack(this.records[0]);
      } else {
        console.warn('empty records');
      }
    }, err => console.error(err));
  }

  /**
   * set track in player and ready to play
   * @param track
   */
  setCurrentPlayedTrack(track) {
    console.log('set current play track');
    if(!track) {
      console.warn('empty track');
      return;
    }
    this.currentPlayedTrack = track;
    this._sharedService.getTrackLink(track.trackLink).subscribe(record => {
      this.streamTrack = record.stream_url + '?nor=1';
      this.durationTime = record.duration;
      this.initWavesurfer();
    });
  }

  playTrack() {
    this.isReady = true;
    this.isRecordPlayed = true;
    this.wavesurfer.play();
    this._sharedService.playPlayerTrackSubject.next(this.currentPlayedTrack);
    //volume control
    this.wavesurfer.setVolume(this.currentVol);
    if(this.isMute) {
      this.wavesurfer.setVolume(0);
    }
  }

  pauseTrack() {
    this.isRecordPlayed = false;
    this.wavesurfer.pause();
    this._sharedService.pausePlayerTrackSubject.next(this.currentPlayedTrack);
  }

  stopTrack() {
    this.isRecordPlayed = false;
    this.wavesurfer.stop();
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
    this.wavesurfer.toggleMute();
    this.isMute = this.wavesurfer.getMute();
    if(this.currentVol != 0) {
      this._casheVolume = this.currentVol;
      this.currentVol = 0;
    } else {
      this.currentVol = this._casheVolume;
    }

  }

  changeVolume(e) {
    let val = e.target.value;
    this.currentVol = val;
    this.wavesurfer.setVolume(val);
    if(val <= 0) {
      this.isMute = !this.isMute;
    } else if(val > 0){
      this.isMute = false;
    }
  }

  getAudioTrackById(id: number) {
    return this.records.find((elem) => <string>elem.id === id.toString());
  }

  playNextTrack() {
    this.isShuffleOn ? this.shuffleTracks() : this.setCurrentPlayedTrack(this.getNextTrack());
    this._sharedService.playPlayerTrackSubject.next(this.currentPlayedTrack);
  }

  playPreviousTrack() {
    this.setCurrentPlayedTrack(this.getPreviousTrack());
    this._sharedService.playPlayerTrackSubject.next(this.currentPlayedTrack);
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

  getCurrentAudioTrackIndex() {
    return this.records.indexOf(this.getAudioTrackById(this.currentPlayedTrack.id));
  }

  togglePlayer() {
    this.isBig = !this.isBig;
  }

  toggleQueue() {
    this.isQueue = !this.isQueue;
  }

}
