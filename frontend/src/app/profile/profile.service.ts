import { Injectable } from '@angular/core';
import {ApiService} from "../shared/services/api.service";
import {environment} from "../../environments/environment";
import {Observable} from "rxjs/Observable";

@Injectable()
export class ProfileService {

  constructor(private _apiService: ApiService) { }

  getProfileData(userName: string) {
    //return this._apiService.post('/' + userName, environment.creds);
    return new Observable(observer => {
      setTimeout(() => {
        observer.next({
          user_image: 'http://test.musify.selectotech.com/assets/upload/track/373/373/3e21708b39264970121a45d09002f65b2023166080.jpeg',
          user_type: 'user',
          firstname: 'alex',
          lastname: 'sol',
          followers: '30',
          following: '34',
          follow_status: false,
          followingId: '23',
          username: userName,
          my_profile: true,
          user_roles_ar: ["Photographer", "Game Developer"],
          loggedin: true,
          playlists: [{}, {}]
        });
      }, 1000);
    })
  }

}
