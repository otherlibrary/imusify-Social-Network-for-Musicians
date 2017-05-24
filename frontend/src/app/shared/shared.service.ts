import { Injectable } from '@angular/core';
import {Observable, Subject} from "rxjs";
import {environment} from "../../environments/environment";
import {ITracksData} from "../interfases";
import {ApiService} from "app/shared/services/api.service";

@Injectable()
export class SharedService {
  public host: string;
  //login
  public loginSubject: Subject<Object> = new Subject<Object>();

  //Subject audio player
  public playPlayerTrackSubject: Subject<Object> = new Subject<Object>();
  public pausePlayerTrackSubject: Subject<Object> = new Subject<Object>();

  //Subject track
  public playTrackSubject: Subject<Object> = new Subject<Object>();
  public pauseTrackSubject: Subject<Object> = new Subject<Object>();

  //Notification
  public notificationSubject: Subject<Object> = new Subject<Object>();

  //set playlist in player
  public setPlaylistSubject: Subject<Object> = new Subject<Object>();


  constructor(private _apiService: ApiService) {}

  getMusic(): Observable<ITracksData> {
    return this._apiService.post('/', environment.creds);
  }

  getTrackLink(url) {
    return this._apiService.get('/data_api?url=' + url)
  }

  getUserRoles() {
    return this._apiService.get(environment.getUserRoles);
  }

  setUserRoles(roles) {
    return this._apiService.post(environment.setUserRoles, roles);
  }
}
