import {Injectable} from '@angular/core';
import {environment} from "../../environments/environment";
import {UploadFileData} from "../interfases";
import {UploadTrackInfo} from "../models";
import {ApiService} from "../shared/services/api.service";


@Injectable()
export class UploadService {
  public uploadTrackInfo: UploadFileData;
  public trackImage: any;
  public wavesurfer: any = null;

  constructor(private _apiService: ApiService) {
    this.uploadTrackInfo = new UploadTrackInfo();
    this.trackImage = {
      data: ''
    };
  }

  getTrackList() {
    return this._apiService.post(environment.uploadTrackList, environment.creds);
  }
}
