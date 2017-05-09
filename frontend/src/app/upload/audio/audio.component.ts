import {Component, OnDestroy, OnInit} from '@angular/core';
import {UploadService} from "../upload.service";
import {EmitterService} from "../../shared/services/emitter.service";
import {UploadAudioData} from "../../interfases/upload/IUploadAudioData";
import {IUploadDetails} from "../../interfases/upload/IUploadDetails";
import {Genre} from "../../interfases/IGenre";
import {IOption} from "ng-select";
import {IMood} from "../../interfases/IMood";

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
  public genres: IOption[];
  public secondGenres: IOption[];
  public typeList: IOption[];
  public moodList: IOption[];

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
