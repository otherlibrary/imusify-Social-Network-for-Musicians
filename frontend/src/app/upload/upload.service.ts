import {Injectable} from '@angular/core';
import {environment} from "../../environments/environment";
import {UploadFileData} from "../interfases";
import {UploadTrackInfo} from "../models";
import {ApiService} from "../shared/services/api.service";
import {Subject} from "rxjs/Subject";


@Injectable()
export class UploadService {
  public uploadTrackInfo: UploadFileData;
  public trackImage: any;
  public wavesurfer: any = null;
  public editPopupSubject: Subject<Object> = new Subject<Object>();

  constructor(private _apiService: ApiService) {
    this.uploadTrackInfo = new UploadTrackInfo();
    this.trackImage = {
      data: ''
    };
  }

  getTrackList() {
    return this._apiService.post(environment.uploadTrackList, environment.creds);
  }

  getUploadDetails() {
    return this._apiService.post(environment.uploadDetails, environment.creds);
  }

  uploadTrackDetails(formData) {
    return this._apiService.post(environment.uploadTrackInfo, formData);
  }

  uploadImageTrack(img) {
    return this._apiService.post(environment.uploadTrackImage, img);
  }

}
