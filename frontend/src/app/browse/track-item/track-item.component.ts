import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {SharedService} from "app/shared/shared.service";
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

  constructor(private _sharedService: SharedService) { }

  ngOnInit() {
  }

  playRecord(record): void {
    this._sharedService.playTrackSubject.next(record);
  }

  toShared(): void {
    this.onsahred.emit(this.record);
  }

  toggle(): void {
    this.isOpen = !this.isOpen;
  }
}
