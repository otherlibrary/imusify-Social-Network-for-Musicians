import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {IRecord} from "../../interfases";

@Component({
  selector: 'track-item-line',
  templateUrl: './track-item.component.html',
  styleUrls: ['./track-item.component.scss']
})
export class TrackItemComponent implements OnInit {
  public isOpen: boolean = false;

  @Input() public record: IRecord;
  @Input() public index: number;

  @Output() onfollow: EventEmitter<any> = new EventEmitter();
  @Output() onsahred: EventEmitter<any> = new EventEmitter();

  constructor() {}

  ngOnInit() {
  }

  toShared(): void {
    this.onsahred.emit(this.record);
  }

  toggle(): void {
    this.isOpen = !this.isOpen;
  }
}
