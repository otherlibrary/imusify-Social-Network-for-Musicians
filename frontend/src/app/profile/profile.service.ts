import { Injectable } from '@angular/core';
import {ApiService} from "../shared/services/api.service";
import {environment} from "../../environments/environment";
import {IProfile, IProfileEdit} from "../interfases";
import {Observable} from "rxjs/Observable";

@Injectable()
export class ProfileService {

  constructor(private _apiService: ApiService) {}

  getCountryList() {
    return this._apiService.get(environment.countryList);
  }

  getStateList(countryId) {
    return this._apiService.get(environment.stateList + countryId);
  }

  getProfileData(userId: string): Observable<IProfile> {
    return this._apiService.get(environment.getProfile + userId);
  }

  getEditProfile(userId: string): Observable<IProfileEdit> {
    return this._apiService.get(environment.editProfile + userId);
  }

  editProfile() {

  }
}
