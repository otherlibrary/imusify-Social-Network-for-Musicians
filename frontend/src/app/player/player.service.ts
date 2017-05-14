import { Injectable } from '@angular/core';
import {Subject} from "rxjs/Subject";
import {ApiService} from "../shared/services/api.service";

@Injectable()
export class PlayerService {
  public wavesurfer: any;
  public playerSubject: Subject<Object> = new Subject<Object>();
  public playerEventSubject: Subject<Object> = new Subject<Object>();

  constructor(private _apiService: ApiService) {
    console.warn('constructor player service init');
  }

  getTrackLink(url) {
    return this._apiService.get('/data_api?url=' + url)
  }
}
