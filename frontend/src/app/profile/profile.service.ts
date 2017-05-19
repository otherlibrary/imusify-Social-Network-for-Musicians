import { Injectable } from '@angular/core';
import {ApiService} from "../shared/services/api.service";
import {environment} from "../../environments/environment";

@Injectable()
export class ProfileService {

  constructor(private _apiService: ApiService) { }

  getUserData(userName: string) {
    return this._apiService.post('/' + userName, environment.creds);
  }

}
