import {Injectable} from '@angular/core';
import {Http, Response} from "@angular/http";
import {contentHeaders} from "../common/headers";
import {Observable} from "rxjs";
import {environment} from "../../environments/environment";
import {UploadFileData} from "../interfases";
import {UploadTrackInfo} from "../models";


@Injectable()
export class UploadService {
  public host: Object = {};
  public uploadTrackInfo: UploadFileData;
  public trackImage: any;

  constructor(private _http: Http) {
    this.host = environment.host;
    this.uploadTrackInfo = new UploadTrackInfo();
    this.trackImage = {
      data: ''
    };
  }

  getTrackList() {
    return this._http.post(this.host + environment.uploadTrackList, environment.creds, {
      headers: contentHeaders,
      withCredentials: true
    })
      .map((res: Response) => res.json())
      .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }
}
