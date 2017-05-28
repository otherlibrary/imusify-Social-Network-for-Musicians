import { Injectable } from '@angular/core';
import {Subject} from "rxjs/Subject";
import {environment} from "../../environments/environment";
import {ApiService} from "app/shared/services/api.service";

@Injectable()
export class SharedService {
  public host: string;
  //login
  public loginSubject: Subject<Object> = new Subject<Object>();

  //Notification
  public notificationSubject: Subject<Object> = new Subject<Object>();

  //set playlist in player
  public setPlaylistSubject: Subject<Object> = new Subject<Object>();


  constructor(private _apiService: ApiService) {}

  getUserRoles() {
    return this._apiService.get(environment.getUserRoles);
  }

  setUserRoles(roles) {
    return this._apiService.post(environment.setUserRoles, roles);
  }
}
