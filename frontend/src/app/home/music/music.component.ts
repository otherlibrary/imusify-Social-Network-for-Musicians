import {Component, OnInit} from '@angular/core';
import {HomeService} from "../home.service";
import {EmitterService} from "../../shared/services/emitter.service";
import {SharedService} from "../../shared/shared.service";

@Component({
    selector: 'app-music',
    templateUrl: './music.component.html',
    styleUrls: ['./music.component.css']
})
export class MusicComponent implements OnInit {
    sharedUrl: null;
    isVisible: boolean;
    records: any;

    constructor(private _homeService: HomeService, private _sharedService: SharedService) {
    }

    ngOnInit() {
        console.log('musik init');
        this.getMusic()
    }

    /**
     * add to follow records
     * @param data
     */
    addFollow(data) {
        this._homeService.addFollow(data).subscribe(data => {
            console.log(data);
        })
    }

    sharedRecord(link) {
        this.sharedUrl = link;
    }

    /**
     * get music data
     */
    getMusic() {
        EmitterService.get('TOGGLE_PRELOADER').emit(true);

        this._sharedService.getMusic().subscribe(data => {
            this.records = data.records;
            EmitterService.get('GET_PROFILE').emit(data);

            EmitterService.get('TOGGLE_PRELOADER').emit(false);
        });
    }
}
