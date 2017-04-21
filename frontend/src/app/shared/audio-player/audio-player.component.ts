import {Component, OnInit, NgZone} from '@angular/core';
import {AudioRecord} from '../../interfases/audio-record';
import {SharedService} from '../shared.service';
import * as _ from 'lodash';
import {Observable} from 'rxjs/Observable';
declare const WaveSurfer: any;

@Component({
  selector: 'app-audio-player',
  templateUrl: './audio-player.component.html',
  styleUrls: ['./audio-player.component.scss']
})
export class AudioPlayerComponent implements OnInit {
  public isShuffleOn: boolean;
  public isTrackRepeated: boolean;
  public isRecordPlayed: boolean;
  public records: Array<AudioRecord>;
  public currentPlayedTrackData: any = null;
  public currentPlayedTrack: any = null;
  public wavesurfer: any = null;
  public streamTrack: string = null;
  public currentTime: number;

  constructor(
    private _sharedService: SharedService,
    public zone: NgZone
  ) {}

  initWavesurfer() {
    if (!this.wavesurfer) {
      this.wavesurfer = WaveSurfer.create({
        container: '#waveform',
        height: 55,
        progressColor: '#c23a48',
        cursorColor: '#fff',
        barWidth: 1.2
      });
      this.wavesurfer.load(this.streamTrack);
    } else {
      this.wavesurfer.load(this.streamTrack);
    }
  }

  ngOnInit() {
    this.currentTime = 0;
    const initialize = _.once(() => {
      this.wavesurfer.on('ready', () => {
        this.playTrack();
      });

      this.wavesurfer.on('play', () => {
        //TODO(AlexSol): function single
        Observable.interval(1000).subscribe(i => {
          this.zone.run(() => {
            this.currentTime += 1;
          });
        });
      });
    });
    this.getCurrentPlayList();
    this._sharedService.playTrackSubject.subscribe((track: any) => {
      this.setCurrentPlayedTrack(track);
      initialize();
    });
  }

  getCurrentPlayList(): void {
    this._sharedService.getMusic().subscribe(data => {
      this.records = data.records;
      this.setCurrentPlayedTrack(this.records[6]);
    }, err => console.error(err));
  }

  setCurrentPlayedTrack(track) {
    this.currentPlayedTrack = track;
    this._sharedService.getTrackLink(track.trackLink).subscribe(record => {
      this.streamTrack = record.stream_url + '?nor=1';
      this.initWavesurfer();
    });
  }

  playTrack() {
    this.isRecordPlayed = true;
    this.wavesurfer.play();
  }

  pauseTrack() {
    this.isRecordPlayed = false;
    this.wavesurfer.pause();
  }


  getAudioTrackById(id: number): AudioRecord {
    return <AudioRecord>this.records.find((elem: AudioRecord) => <number>elem.id === id);
  }

  pauseCurrentAudioTrack(): void {
    this.isRecordPlayed = false;
    this.wavesurfer.playPause();
  }

  // playNextTrack() {
  //   this.setCurrentPlayedTrack(this.getNextTrack());
  //   this.playCurrentAudioTrack();
  // }
  //
  // playPreviousTrack() {
  //   this.setCurrentPlayedTrack(this.getPreviousTrack());
  //   this.playCurrentAudioTrack();
  // }

  getNextTrack(): AudioRecord {
    const currentTrackIndex = this.getCurrentAudioTrackIndex();
    if (currentTrackIndex > this.records.length) {
      return this.records[0];
    }
    return this.records[currentTrackIndex + 1];
  }

  getPreviousTrack(): AudioRecord {
    const currentTrackIndex = this.getCurrentAudioTrackIndex();
    if (+currentTrackIndex === 0) {
      return this.records[this.records.length - 1];
    }
    return this.records[currentTrackIndex - 1];
  }

  getCurrentAudioTrackIndex() {
    return this.records.indexOf(this.getAudioTrackById(this.currentPlayedTrackData.id));
  }

}
