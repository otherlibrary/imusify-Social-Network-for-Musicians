import {Injectable} from '@angular/core';
import {environment} from "../../environments/environment";
import {IUploadFileData} from "../interfases";
import {UploadTrackInfo} from "../models";
import {ApiService} from "../shared/services/api.service";
import {Subject} from "rxjs/Subject";
import {Observable} from "rxjs/Observable";
import {IRecord} from "../interfases/IRecord";


@Injectable()
export class UploadService {
  public uploadTrackInfo: IUploadFileData;
  public trackImage: any;
  public wavesurfer: any = null;
  public editPopupSubject: Subject<Object> = new Subject<Object>();

  constructor(private _apiService: ApiService) {
    this.uploadTrackInfo = new UploadTrackInfo();
    this.trackImage = {
      track_id: '',
      file: '',
      type: ''
    };
  }

  /**
   * clear all field in track info
   * @private
   */
  public clearUploadTrackInfo() {
    for(let k in this.uploadTrackInfo) {
      this.uploadTrackInfo[k] = null;
    }
    this.uploadTrackInfo.is_public = "1";
  }

  getTrackList(): Observable<any> {
    return this._apiService.post(environment.uploadTrackList, environment.creds);
  }

  getUploadDetails(): Observable<any> {
    return this._apiService.post(environment.uploadDetails, environment.creds);
  }

  uploadTrackDetails(formData): Observable<any> {
    return this._apiService.post(environment.uploadTrackInfo, formData);
  }

  uploadImageTrack(imgData): Observable<any> {
    return this._apiService.post(environment.uploadTrackImage, imgData);
  }

  getTrackById(trackId) {
    return this._apiService.get(environment.getTrackId + trackId);
  }

  deleteTrack(trackData) {
    return this._apiService.post(environment.deleteTrack, trackData);
  }

  getLicensesList() {
    return this._apiService.get(environment.licensesList);
  }

}
