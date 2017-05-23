import { Injectable } from '@angular/core';
import {ApiService} from "../shared/services/api.service";
import {environment} from "../../environments/environment";
import {IProfile, IProfileEdit} from "../interfases";
import {Observable} from "rxjs/Observable";
import {IOption} from "ng-select";

@Injectable()
export class ProfileService {

  constructor(private _apiService: ApiService) {}

  getCountryList(): Observable<IOption[]> {
    return this._apiService.get(environment.countryList);
  }

  getStateList(countryId: string): Observable<IOption[] > {
    return this._apiService.get(environment.stateList + countryId);
  }

  getCityList(stateId: string): Observable<IOption[] > {
    return this._apiService.get(environment.cityList + stateId);
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
