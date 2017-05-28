import {Component, Input, OnDestroy, OnInit} from '@angular/core';
import {IRecord} from "../../interfases/IRecord";
import {Subscription} from "rxjs/Subscription";
import {PlayerService} from "../player.service";
import {IRecordEvent} from "../../interfases/IRecordEvent";

@Component({
  selector: 'app-play-pause',
  templateUrl: './play-pause.component.html',
  styleUrls: ['./play-pause.component.scss']
})
export class PlayPauseComponent implements OnInit, OnDestroy {
  @Input() record: IRecord;

  public isPlayed: boolean;
  private playerEventSubscription: Subscription;

  constructor(private _playerService: PlayerService) {
  }

  ngOnInit() {
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
