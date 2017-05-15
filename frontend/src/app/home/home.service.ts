import {Injectable} from '@angular/core';
import {environment} from '../../environments/environment';
import {Observable} from 'rxjs';
import {ApiService} from "../shared/services/api.service";
import {ITracksData} from "../interfases/ITracksData";

@Injectable()
export class HomeService {

  constructor(private _apiService: ApiService) {}

  getAllNews(): Observable<ITracksData> {
    return this._apiService.post('/', environment.creds);
  }

  getMusic(): Observable<ITracksData> {
    return this._apiService.post(environment.musicList, environment.creds);
  }

  /**
   * follow link
   * @param data
   * @returns {Observable<R>}
   */
  addFollow(data) {
    // const str = this._helpersService.toStringParam(data);
    // return this._http.post(this.host + '/follow', str, {
    //   headers: contentHeaders,
    //   withCredentials: true
    // })
    //   .map((res: Response) => res.json())
    //   .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }
}
