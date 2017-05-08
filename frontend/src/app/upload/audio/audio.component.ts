import {Component, OnDestroy, OnInit} from '@angular/core';
import {UploadService} from "../upload.service";
import {EmitterService} from "../../shared/services/emitter.service";
import {UploadAudioData} from "../../interfases/upload/IUploadAudioData";
import {IUploadDetails} from "../../interfases/upload/IUploadDetails";

@Component({
    selector: 'app-audio',
    templateUrl: './audio.component.html',
    styleUrls: ['./audio.component.scss']
})
export class AudioComponent implements OnInit, OnDestroy {
    public trackList: Object[] = [];
    public isOpenEdit: boolean = false;
    public editSubscriber: any;
    public uploadAudioData: UploadAudioData;
    public uploadDetails: IUploadDetails;

    constructor(private _uploadService: UploadService) {
    }

    ngOnInit() {
        this.getTrackList();
        this.getUploadDetails();
        //edit track popup
        this.editSubscriber = this._uploadService.editPopupSubject.subscribe((flag: boolean) => {
            this.isOpenEdit = flag;
        });
    }

    ngOnDestroy() {
        this.editSubscriber.unsubscriber();
    }

    getUploadDetails() {
        this._uploadService.getUploadDetails().subscribe((data: IUploadDetails) => {
            this.uploadDetails = data;
        })
    }

    getTrackList() {
        EmitterService.get('TOGGLE_PRELOADER').emit(true);
        this._uploadService.getTrackList().subscribe((data: UploadAudioData) => {
            this.uploadAudioData = data;
            this.trackList = data.data_array;
            EmitterService.get('TOGGLE_PRELOADER').emit(false);
        }, err => {
          console.log(err);
        });
    }
}
