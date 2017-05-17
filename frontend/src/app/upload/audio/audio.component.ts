import {Component, OnDestroy, OnInit} from '@angular/core';
import {UploadService} from "../upload.service";
import {EmitterService} from "../../shared/services/emitter.service";
import {UploadAudioData} from "../../interfases/upload/IUploadAudioData";
import {IUploadDetails} from "../../interfases/upload/IUploadDetails";
import {Genre} from "../../interfases/IGenre";
import {IOption} from "ng-select";
import {IMood} from "../../interfases/IMood";
import {IRecord} from "../../interfases/IRecord";
import {HelpersService} from "../../shared/services/helpers.service";
import {SharedService} from "../../shared/shared.service";

@Component({
  selector: 'app-audio',
  templateUrl: './audio.component.html',
  styleUrls: ['./audio.component.scss']
})
export class AudioComponent implements OnInit, OnDestroy {
  public trackList: Object[] = [];
  public isOpenEdit: boolean = false;
  public editSubscriber: any;
  public editTrack: any;

  public uploadAudioData: UploadAudioData;
  public uploadDetails: IUploadDetails;
  public genres: IOption[];
  public secondGenres: IOption[];
  public typeList: IOption[];
  public moodList: IOption[];
  public licensesList: any[];

  constructor(
    private _uploadService: UploadService,
    private _helperService: HelpersService,
    private _sharedService: SharedService
  ) {}

  ngOnInit() {
    this.getTrackList();
    this.getUploadDetails();
    this.getLicensesList();
    //edit track popup
    this.editSubscriber = this._uploadService.editPopupSubject.subscribe((flag: boolean) => {
      this.isOpenEdit = flag;
      if(!this.isOpenEdit) {
        this.getTrackList();
        this.getUploadDetails();
      }
    });
  }

  ngOnDestroy() {
    this.editSubscriber.unsubscribe();
  }

  getUploadDetails() {
    this._uploadService.getUploadDetails().subscribe((data: IUploadDetails) => {
      this.uploadDetails = data;

      this.genres = data.genre.map((genre: Genre) => {
        return {value: genre.id, label: genre.genre};
      });
      this.secondGenres = data.sec_genre.map((genre: Genre) => {
        return {value: genre.id, label: genre.genre};
      });
      this.typeList = data.track_upload_type_list.map((type: Genre) => {
        return {value: type.id, label: type.name};
      });
      this.moodList = data.mood_list.map((mood: IMood) => {
        return {value: mood.id, label: mood.mood};
      });
    })
  }

  getLicensesList() {
    this._uploadService.getLicensesList().subscribe(licenses => {
      this.licensesList = licenses;
    });
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

  getTrackById(trackId) {
    console.log(trackId);
    this._uploadService.getTrackById(trackId).subscribe((record: IRecord) => {
      this.editTrack = record;
      console.log(this.editTrack);
      this.isOpenEdit = true;
    }, err => {
      console.error(err);
    })
  }

  removeTrack(record) {
    let delTrack = {
      userId: record.userId,
      trackId: record.id,
      deltype: "au"
    };
    let req = this._helperService.toStringParam(delTrack);
    this._uploadService.deleteTrack(req).subscribe(res => {
      console.log(res);
      if(res.status === "success") {
        let index: number = this.trackList.indexOf(record);
        if (index !== -1) {
          this.trackList.splice(index, 1);
        }
        this._sharedService.notificationSubject.next({
          title: 'Remove track',
          msg: 'success',
          type: 'success'
        });
      }
    }, err => {
      this._sharedService.notificationSubject.next({
        title: 'Remove track',
        msg: 'error',
        type: 'error'
      });
    })
  }

}
