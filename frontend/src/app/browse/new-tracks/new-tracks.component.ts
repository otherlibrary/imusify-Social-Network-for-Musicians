import {Component, OnInit} from '@angular/core';
import {BrowseService} from "../browse.service";
import {ITracksData, IRecord} from "../../interfases";
import {EmitterService} from "../../shared/services/emitter.service";

@Component({
    selector: 'app-new-tracks',
    templateUrl: './new-tracks.component.html',
    styleUrls: ['./new-tracks.component.scss']
})
export class NewTracksComponent implements OnInit {
    public newSongsData: ITracksData;
    public records: IRecord[];

    constructor(private _BrowseService: BrowseService) {
    }

    ngOnInit() {
        this.getNewSongs();
    }

    getNewSongs() {
        EmitterService.get('TOGGLE_PRELOADER').emit(true);
        this._BrowseService.getNewSongs().subscribe(data => {
            this.newSongsData = data;
            this.records = data.data_array;
            EmitterService.get('TOGGLE_PRELOADER').emit(false);
        });
    }
}
