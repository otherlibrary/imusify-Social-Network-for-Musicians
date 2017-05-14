import {Component, OnInit, Output, EventEmitter, OnDestroy, Input} from '@angular/core';
import {DomSanitizer} from "@angular/platform-browser";
import {SharedService} from "../../shared/shared.service";
import {IRecord} from "../../interfases/IRecord";
import {Subscription} from "rxjs/Subscription";
import {PlayerService} from "../../player/player.service";
import {IRecordEvent} from "../../interfases/IRecordEvent";

@Component({
  selector: 'home-record',
  templateUrl: './record.component.html',
  styleUrls: ['record.component.scss']
})
export class RecordComponent implements OnInit, OnDestroy {
  @Output() onfollow: EventEmitter<any> = new EventEmitter();
  @Output() onsahred: EventEmitter<any> = new EventEmitter();

  @Input() record: IRecord;

  public isPlayed: boolean;
  private playerEventSubscription: Subscription;

  constructor(private sanitizer: DomSanitizer,
              public _playerService: PlayerService) {
  }

  ngOnInit() {
    //play/pause
    this.playerEventSubscription = this._playerService.playerOutputSubject
      .subscribe((res: IRecordEvent) => {
        if (res.type === 'play') {
          this.isPlayed = res.record.id === this.record.id;
        }
        if (res.type === 'pause') {
          if (res.record.id === this.record.id) {
            this.isPlayed = false;
          }
        }
      });
  }

  ngOnDestroy() {
    this.playerEventSubscription.unsubscribe();
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

  playRecord(): void {
    this._playerService.playInputSubject.next({
      type: 'play',
      record: this.record
    });
  }

  pauseRecord(): void {
    this._playerService.playInputSubject.next({
      type: 'pause',
      record: this.record
    });
  }

}
