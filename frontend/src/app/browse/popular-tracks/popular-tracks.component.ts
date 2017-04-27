import {Component, OnInit} from '@angular/core';
import {BrowseService} from "../browse.service";
import {EmitterService} from "../../shared/services/emitter.service";

@Component({
    selector: 'app-popular-tracks',
    templateUrl: './popular-tracks.component.html',
    styleUrls: ['./popular-tracks.component.scss']
})
export class PopularTracksComponent implements OnInit {
    public data: {};
    public records: any[];

    constructor(private _browseService: BrowseService) {}

    ngOnInit() {
        this.getPopularRecords();
    }

    getPopularRecords() {
        EmitterService.get('TOGGLE_PRELOADER').emit(true);

        this._browseService.getPopularRecords().subscribe(data => {
            this.data = data;
            this.records = data.data_array;
            console.log(this.records);
            EmitterService.get('TOGGLE_PRELOADER').emit(false);
        });
    }

}
