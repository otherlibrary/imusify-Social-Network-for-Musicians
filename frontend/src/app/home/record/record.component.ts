import {Component, OnInit, Output, EventEmitter, Input} from '@angular/core';
import {IRecord} from "../../interfases/IRecord";

@Component({
  selector: 'home-record',
  templateUrl: './record.component.html',
  styleUrls: ['record.component.scss']
})
export class RecordComponent implements OnInit {
  @Output() onfollow: EventEmitter<any> = new EventEmitter();
  @Output() onsahred: EventEmitter<any> = new EventEmitter();

  @Input() record: IRecord;

  constructor() {
  }

  ngOnInit() {
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
}
