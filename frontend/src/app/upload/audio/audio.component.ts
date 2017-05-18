import {Component, OnDestroy, OnInit} from '@angular/core';
import {UploadService} from "../upload.service";
import {EmitterService} from "../../shared/services/emitter.service";
import {UploadAudioData} from "../../interfases/upload/IUploadAudioData";
import {IUploadDetails} from "../../interfases/upload/IUploadDetails";
import {IGenre} from "../../interfases/IGenre";
import {IOption} from "ng-select";
import {IMood} from "../../interfases/IMood";
import {HelpersService} from "../../shared/services/helpers.service";
import {SharedService} from "../../shared/shared.service";
import {IEditTrack} from "../../interfases/IEditTrack";

@Component({
  selector: 'app-audio',
  templateUrl: './audio.component.html',
  styleUrls: ['./audio.component.scss']
})
export class AudioComponent implements OnInit, OnDestroy {
  public trackList: Object[] = [];
  public isOpenEdit: boolean = false;
  public editSubscriber: any;
  public editTrack: IEditTrack;
  public typePriceLicense: string = 'mp3';
  public openSwitch: boolean = false;

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
      this._uploadService.clearUploadTrackInfo();
      this.openSwitch = false;
      
      this.isOpenEdit = flag;
      if(!this.isOpenEdit) {
        this.getLicensesList();
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

      this.genres = data.genre.map((genre: IGenre) => {
        return {value: genre.id, label: genre.genre};
      });
      this.secondGenres = data.sec_genre.map((genre: IGenre) => {
        return {value: genre.id, label: genre.genre};
      });
      this.typeList = data.track_upload_type_list.map((type: IGenre) => {
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
      this.typePriceLicense = "mp3";
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
    this._uploadService.getTrackById(trackId).subscribe((record: any) => {
      this.editTrack = record.track;
      console.log('editTrack: ', this.editTrack);
      this.typePriceLicense = 'licencePrice';

      this._uploadService.uploadTrackInfo.track_id = this.editTrack.trackId;
      this._uploadService.uploadTrackInfo.desc = this.editTrack.description;
      this._uploadService.uploadTrackInfo.title = this.editTrack.title;
      this._uploadService.uploadTrackInfo.is_public = this.editTrack.isPublic == "y" ? "1" : null;
      this._uploadService.uploadTrackInfo.genre_id = this.editTrack.genreId;
      this._uploadService.uploadTrackInfo.track_upload_type = this.editTrack.trackuploadType;
      this._uploadService.uploadTrackInfo.type_artist = this.editTrack.track_musician_type;
      this._uploadService.uploadTrackInfo.secondary_genre_id = this.editTrack.secondary_genres;
      this._uploadService.uploadTrackInfo.pick_moods_id = this.editTrack.moods;
      this.licensesList = this.editTrack.licences;

      if(this.editTrack.track_musician_type === 'm') {
        this._uploadService.uploadTrackInfo.type_artist = 'male';
      }
      if(this.editTrack.track_musician_type === 'f') {
        this._uploadService.uploadTrackInfo.type_artist = 'female';
      }
      if(this.editTrack.track_musician_type === 'b') {
        this._uploadService.uploadTrackInfo.type_artist = 'both';
      }
      this._uploadService.uploadTrackInfo.release_date = {
        date: {
          year: this.editTrack.release_yy,
          month: this.editTrack.release_mm,
          day: this.editTrack.release_dd
        }
      };
      this.isOpenEdit = true;
      this.openSwitch = true;
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
