import { Injectable } from '@angular/core';
import {Observable} from "rxjs/Observable";
import {environment} from "../../environments/environment";
import {ITracksData, IArtistData} from "../interfases";
import {ApiService} from "../shared/services/api.service";

@Injectable()
export class BrowseService {
  constructor(private _apiService: ApiService) {}

  getPopularRecords(): Observable<ITracksData> {
    return this._apiService.post(environment.browse, environment.creds)
  }

  getNewSongs(): Observable<ITracksData> {
    return this._apiService.post(environment.browseNewSongs, environment.creds);
  }

  getPopularArtist(): Observable<IArtistData>  {
    return this._apiService.post(environment.browsePopularArtist, environment.creds);
  }
}
