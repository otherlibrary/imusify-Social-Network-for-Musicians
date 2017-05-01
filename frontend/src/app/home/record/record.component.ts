import {Component, OnInit, Output, EventEmitter, OnDestroy, Input} from '@angular/core';
import {DomSanitizer} from "@angular/platform-browser";
import {SharedService} from "../../shared/shared.service";
import {IRecord} from "../../interfases/IRecord";
import {Subscription} from "rxjs/Subscription";

@Component({
  selector: 'home-record',
  templateUrl: './record.component.html',
  styleUrls: ['record.component.scss']
})
export class RecordComponent implements OnInit, OnDestroy {
  @Output() onfollow: EventEmitter<any> = new EventEmitter();
  @Output() onsahred: EventEmitter<any> = new EventEmitter();
  @Output() onNext: EventEmitter<any> = new EventEmitter();
  @Input() record: IRecord;
  public isPlayed: boolean;
  private nextTrackSubscription: Subscription;

  constructor(
    private sanitizer: DomSanitizer,
    private _sharedService: SharedService
  ) {}

  ngOnInit() {
    this.nextTrackSubscription = this._sharedService.nextTrackSubject.subscribe((record: IRecord) => {
      this.isPlayed = record.id === this.record.id;
    });
  }

  ngOnDestroy() {
    this.nextTrackSubscription.unsubscribe();
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

  playRecord(record): void {
    this._sharedService.nextTrackSubject.next(record);
    this._sharedService.playTrackSubject.next(record);
    this.isPlayed = true;
    this.onNext.emit(record);
  }

  pauseRecord(record): void {
    this._sharedService.pauseTrackSubject.next(record);
    this.isPlayed = false;
  }

}
