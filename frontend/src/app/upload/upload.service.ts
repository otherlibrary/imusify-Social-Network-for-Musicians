import {Injectable, Inject} from '@angular/core';
import {Http, Response} from "@angular/http";
import {contentHeaders} from "../common/headers";
import {Observable} from "rxjs";
import {environment} from "../../environments/environment";
import {INewTrack} from "../interfases/new-track";

const trackList = [
  {"id": "10", "name": "test"},
  {"id": "11", "name": "test"},
  {"id": "12", "name": "test"},
  {"id": "13", "name": "test"},
];

@Injectable()
export class UploadService {
  public host: Object = {};
  //TODO(AlexSol): intarface

  public uploadTrackInfo: any = {};

  constructor(private _http: Http) {
    this.host = environment.host;
  }

  getTrackById(id) {
    return trackList.filter(track => {
      return track.id === id;
    });
  }

  getTrackList() {
    const creds = 'ajax=true';
    return this._http.post(this.host + environment.uploadTrackList, creds, {
      headers: contentHeaders,
      withCredentials: true
    })
      .map((res: Response) => res.json())
      .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }

  uploadTrack(chunk: Blob, name: string, randomStr: string) {
    const formData = new FormData();

    formData.append('ajax', true);
    formData.append('r', randomStr);
    formData.append('files[]', chunk, name);

    return this._http.post(this.host + environment.uploadFilesUrl, formData, {
      withCredentials: true
    })
      .map((res: Response) => res.json())
      .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }

  uploadTrackImage(img, type) {
    const formData = new FormData();
    formData.append('img', img);
    formData.append('imgType', type);

    return this._http.post(this.host + environment.uploadTrackImage, formData, {
      withCredentials: true
    })
      .map((res: Response) => res.json())
      .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }

  generateRandomString(): string {
    return (Math.random() * 10000000).toString();
  }

  saveTrack() {
    const formData = new FormData();
    for (let key in this.uploadTrackInfo) {
      formData.append(key, this.uploadTrackInfo[key]);
    }
    return this._http.post(this.host + environment.saveTrack, formData, {
      withCredentials: true
    })
      .map((res: Response) => res.json())
      .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }
}
