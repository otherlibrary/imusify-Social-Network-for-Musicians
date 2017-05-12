import {Component, OnInit, Input, Output, EventEmitter, OnDestroy} from '@angular/core';
import {SharedService} from "../../shared/shared.service";
import {IRecord} from "../../interfases/IRecord";
import {Subscription} from "rxjs/Subscription";

@Component({
    selector: 'app-track',
    templateUrl: './track.component.html',
    styleUrls: ['./track.component.scss']
})
export class TrackComponent implements OnInit, OnDestroy {
    @Input() record: IRecord;
    @Input() isArticle: boolean;
    @Output() onNext: EventEmitter<any> = new EventEmitter();

    public isPlayed: boolean;
    private pausePlayerTrackSubscription: Subscription;
    private playPlayerTrackSubscription: Subscription;

    constructor(private _sharedService: SharedService) {
    }

    ngOnInit() {
        //pause track
        this.pausePlayerTrackSubscription = this._sharedService.pausePlayerTrackSubject.subscribe((record: IRecord) => {
            if(record.id === this.record.id) {
                this.isPlayed = false;
            }
        });
        //play track
        this.playPlayerTrackSubscription = this._sharedService.playPlayerTrackSubject.subscribe((record: IRecord) => {
            this.isPlayed = record.id === this.record.id;
        });
    }
    ngOnDestroy() {
        this.pausePlayerTrackSubscription.unsubscribe();
        this.playPlayerTrackSubscription.unsubscribe();
    }

    playRecord(record): void {
        this._sharedService.playTrackSubject.next(record);
        this.isPlayed = true;
        this.onNext.emit(record);
    }

    pauseRecord(record): void {
        this._sharedService.pauseTrackSubject.next(record);
        this.isPlayed = false;
    }

}
