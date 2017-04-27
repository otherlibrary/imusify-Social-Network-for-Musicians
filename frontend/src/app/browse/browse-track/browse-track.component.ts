import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {SharedService} from "app/shared/shared.service";

@Component({
  selector: 'app-browse-track',
  templateUrl: './browse-track.component.html',
  styleUrls: ['./browse-track.component.scss']
})
export class BrowseTrackComponent implements OnInit {
  public isOpen: boolean = false;

  @Input() public record: any;
  @Input() public index: any;

  @Output() onfollow: EventEmitter<any> = new EventEmitter();
  @Output() onsahred: EventEmitter<any> = new EventEmitter();

  constructor(private _sharedService: SharedService) { }

  ngOnInit() {
  }

  playRecord(record) {
    this._sharedService.playTrackSubject.next(record);
  }

  toShared() {
    console.log(this.record);
    this.onsahred.emit(this.record);
  }

  toggle() {
    this.isOpen = !this.isOpen;
  }
}
