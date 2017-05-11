import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {IRecord} from "../../interfases/IRecord";

@Component({
  selector: 'app-track-list',
  templateUrl: './track-list.component.html',
  styleUrls: ['./track-list.component.scss']
})
export class TrackListComponent implements OnInit {
  @Input() trackList: IRecord[];
  @Input() currentPlayed: IRecord[];
  @Output() play: EventEmitter<any> = new EventEmitter();

  constructor() { }

  ngOnInit() {
  }

  playTrack(track) {
    this.play.emit(track);
  }
}
