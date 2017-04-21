import {Component, OnInit} from '@angular/core';
import {UploadService} from "../upload.service";
import {EmitterService} from "../../shared/services/emitter.service";

@Component({
    selector: 'app-audio',
    templateUrl: './audio.component.html',
    styleUrls: ['./audio.component.scss']
})
export class AudioComponent implements OnInit {
    public trackList: Object[] = [];

    constructor(private uploadService: UploadService) {
    }

    ngOnInit() {
        this.getTrackList();
    }

    getTrackList() {
        EmitterService.get('TOGGLE_PRELOADER').emit(true);
        this.uploadService.getTrackList().subscribe(data => {
            this.trackList = data.data_array;
            EmitterService.get('TOGGLE_PRELOADER').emit(false);
        }, err => {
          console.log(err);
        });
    }
}
