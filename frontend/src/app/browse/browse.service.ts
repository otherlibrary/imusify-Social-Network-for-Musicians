import { Injectable } from '@angular/core';
import {contentHeaders} from "../common/headers";
import {Observable} from "rxjs/Observable";
import {Http} from "@angular/http";
import {environment} from "../../environments/environment";
import {ITracksData, IArtistData} from "../interfases";

@Injectable()
export class BrowseService {

  public host: string;

  constructor(private _http: Http) {
    this.host = environment.host;
  }


  getPopularRecords(): Observable<ITracksData> {
    return this._http.post(this.host + '/browse', environment.creds, {
      withCredentials: true,
      headers: contentHeaders
    })
      .map((res) => res.json())
      .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }

  getNewSongs(): Observable<ITracksData> {
    return this._http.post(this.host + '/browse/new-songs', environment.creds, {
      withCredentials: true,
      headers: contentHeaders
    })
      .map((res) => res.json())
      .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }

  getPopularArtist(): Observable<IArtistData>  {
    return this._http.post(this.host + '/browse/popular-artist', environment.creds, {
      withCredentials: true,
      headers: contentHeaders
    })
      .map((res) => res.json())
      .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }
}
