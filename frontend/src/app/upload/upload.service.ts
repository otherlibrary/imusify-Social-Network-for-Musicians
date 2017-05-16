import {Injectable} from '@angular/core';
import {environment} from "../../environments/environment";
import {UploadFileData} from "../interfases";
import {UploadTrackInfo} from "../models";
import {ApiService} from "../shared/services/api.service";
import {Subject} from "rxjs/Subject";
import {Observable} from "rxjs/Observable";
import {IRecord} from "../interfases/IRecord";


@Injectable()
export class UploadService {
  public uploadTrackInfo: UploadFileData;
  public trackImage: any;
  public wavesurfer: any = null;
  public editPopupSubject: Subject<Object> = new Subject<Object>();

  constructor(private _apiService: ApiService) {
    this.uploadTrackInfo = new UploadTrackInfo();

    this.trackImage = {
      track_id: '',
      file: '',
      type: ''
    };
  }

  getTrackList(): Observable<any> {
    return this._apiService.post(environment.uploadTrackList, environment.creds);
  }

  getUploadDetails(): Observable<any> {
    return this._apiService.post(environment.uploadDetails, environment.creds);
  }

  uploadTrackDetails(formData): Observable<any> {
    return this._apiService.post(environment.uploadTrackInfo, formData);
  }

  uploadImageTrack(imgData): Observable<any> {
    return this._apiService.post(environment.uploadTrackImage, imgData);
  }

  getTrackById(trackId) {
    //return this._apiService.get(environment.getTrackId + trackId);
    return new Observable(obs => {
      setTimeout(() => {
        obs.next({
          filename: 'test',
          track_id: '21',
          waveform: '',
          title: 'test',
          desc: 'lorem',
          release_date: '',
          track_type: '2121',
          genre_id: '21',
          copyright: '1',
          second_genre_id: '2',
          pick_moods: 'dsd',
          type_artist: 'dsdsds',
          is_public: '1',
          album: '',
          single: '',
          advertising: '',
          corporate: '',
          documentaryFilm: '',
          film: '200',
          software: '300',
          internetVideo: '333',
          liveEvent: '333',
          musicHold: '333',
          musicProd1k: '333',
          musicProd10k: '333',
          musicProd50k: '333',
          musicProd51k: '333',
          website: '333',
          advertisingE: '333',
          corporateE: '333',
          documentaryFilmE: '333',
          filmE: '333',
          softwareE: '333',
          internetVideoE: '333',
          liveEventE: '333',
          musicHoldE: '333',
          musicProd1kE: '333',
          musicProd10kE: '333',
          musicProd50kE: '333',
          musicProd51kE: '333',
          websiteE: '333',
          nonProfit: '333',
          neverSale: '333'
        })
      }, 1000);
    })
  }

}
