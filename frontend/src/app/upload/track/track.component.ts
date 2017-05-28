import {Component, OnInit, Input, Output, EventEmitter} from '@angular/core';
import {IRecord} from "../../interfases/IRecord";

@Component({
  selector: 'app-track',
  templateUrl: './track.component.html',
  styleUrls: ['./track.component.scss']
})
export class TrackComponent implements OnInit {
  @Input() record: IRecord;
  @Output() onEdit: EventEmitter<any> = new EventEmitter();
  @Output() onRemove: EventEmitter<any> = new EventEmitter();

  public isPlayed: boolean;

  constructor() {
  }

  ngOnInit() {}

  editTrack(track) {
    this.onEdit.emit(track);
  }

  removeTrack(track) {
    this.onRemove.emit(track);
  }

}
