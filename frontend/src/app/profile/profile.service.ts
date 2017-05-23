import { Injectable } from '@angular/core';
import {ApiService} from "../shared/services/api.service";
import {environment} from "../../environments/environment";
import {IProfile} from "../interfases/profile/IProfile";
import {Observable} from "rxjs/Observable";

@Injectable()
export class ProfileService {

  constructor(private _apiService: ApiService) {}

  getProfileData(userId: string): Observable<IProfile> {
    return this._apiService.get(environment.getProfile + userId);
  }

}
