import { Injectable } from '@angular/core';
import {Subject} from "rxjs/Subject";
import {ApiService} from "../shared/services/api.service";
import {Observable} from "rxjs/Observable";
import {ITracksData} from "../interfases/ITracksData";
import {environment} from "../../environments/environment";

@Injectable()
export class PlayerService {
  public wavesurfer: any;
  public playInputSubject: Subject<Object> = new Subject<Object>();
  public playerOutputSubject: Subject<Object> = new Subject<Object>();

  constructor(private _apiService: ApiService) {
    console.warn('constructor player service init');
  }

  getCurrentPlaylist(): Observable<ITracksData> {
    return this._apiService.post('/', environment.creds);
  }

  getTrackLink(url) {
    return this._apiService.get('/data_api?url=' + url)
  }
}
