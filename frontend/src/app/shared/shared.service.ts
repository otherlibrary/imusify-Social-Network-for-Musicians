import { Injectable } from '@angular/core';
import {Http, Response} from '@angular/http';
import {Observable, Subject} from "rxjs";
import {environment} from "../../environments/environment";
import {contentHeaders} from "../common/headers";
import {ITracksData} from "../interfases";

@Injectable()
export class SharedService {
  public host: string;
  public playTrackSubject: Subject<Object> = new Subject<Object>();
  public pauseTrackSubject: Subject<Object> = new Subject<Object>();
  public nextTrackSubject: Subject<Object> = new Subject<Object>();

  constructor(private _http: Http) {
    this.host = environment.host;
  }

  getMusic(): Observable<ITracksData> {
    return this._http.post(this.host + environment.musicList, environment.creds, {
      headers: contentHeaders,
      withCredentials: true
    })
      .map((res: Response) => res.json())
      .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }

  getTrackLink(url) {
    return this._http.get(this.host + '/data_api?url=' + url)
      .map((res: Response) => res.json())
      .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }
}
