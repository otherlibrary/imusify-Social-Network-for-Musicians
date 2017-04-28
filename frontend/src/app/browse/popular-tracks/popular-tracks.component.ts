import {Component, OnInit} from '@angular/core';
import {BrowseService} from "../browse.service";
import {EmitterService} from "../../shared/services/emitter.service";
import {ITracksData} from "../../interfases";
import {IRecord} from "../../interfases/IRecord";

@Component({
    selector: 'app-popular-tracks',
    templateUrl: './popular-tracks.component.html',
    styleUrls: ['./popular-tracks.component.scss']
})
export class PopularTracksComponent implements OnInit {
    public data: ITracksData;
    public records: IRecord[];

    constructor(private _browseService: BrowseService) {}

    ngOnInit() {
        this.getPopularRecords();
    }

    getPopularRecords() {
        EmitterService.get('TOGGLE_PRELOADER').emit(true);

        this._browseService.getPopularRecords().subscribe((data: ITracksData) => {
            this.data = data;
            this.records = data.data_array;
            EmitterService.get('TOGGLE_PRELOADER').emit(false);
        });
    }

}
