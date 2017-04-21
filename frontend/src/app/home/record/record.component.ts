import {Component, OnInit, Output, EventEmitter} from '@angular/core';
import {DomSanitizer} from "@angular/platform-browser";

@Component({
  selector: 'home-record',
  templateUrl: './record.component.html',
  styleUrls: ['record.component.scss'],
  inputs: ['record']
})
export class RecordComponent implements OnInit {
  @Output() onfollow: EventEmitter<any> = new EventEmitter();
  @Output() onsahred: EventEmitter<any> = new EventEmitter();
  @Output() onPlay: EventEmitter<any> = new EventEmitter();
  record: any;

  constructor(private sanitizer: DomSanitizer) {
  }

  ngOnInit() {
  }

  getWave() {
    // sanitize the style expression
    let wave = this.record.wave;
    return this.sanitizer.bypassSecurityTrustStyle(`url(${wave}`);
  }

  toFollow() {
    let data = {
      toid: this.record.uid,
      refreshpanel: "yes",
      ajax: true
    };
    this.onfollow.emit(data)
  }

  toShared() {
    this.onsahred.emit(this.record.trackLink);
  }

  playTrack(record) {
    this.onPlay.emit(record);
  }
}
