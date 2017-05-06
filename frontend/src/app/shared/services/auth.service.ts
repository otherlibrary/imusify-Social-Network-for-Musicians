import {Injectable} from '@angular/core';
import {Observable} from 'rxjs/Rx';
import {Subject} from "rxjs/Subject";

import {HelpersService} from './helpers.service';
import {environment} from '../../../environments/environment';
import {IUser} from "../../interfases/IUser";
import {ApiService} from "./api.service";


@Injectable()
export class AuthService {
  public profileData: any;
  public loggedin: boolean;
  public redirectUrl: string;
  public cleanUserSubject: Subject<Object> = new Subject<Object>();

  constructor(
    private _apiService: ApiService,
    private _helpersService: HelpersService
  ) {
    this.profileData = JSON.parse(localStorage.getItem('auth_data'));
    this.loggedin = this.profileData;
  }

  /**
   *
   * @param userData {username, password}
   * @returns {Observable<R>}
   */
  login(userData) {
    const params = this._helpersService.toStringParam(userData);
    return this._apiService.post(environment.login, params);
  }

  /**
   *
   * @param newUser
   * @returns {Observable<R>}
   */
  signUp(newUser) {
    return this._apiService.post(environment.signup, newUser);
  }

  /**
   * LOGOUT
   * @returns {Observable<R>}
   */
  logOut() {
    return this._apiService.post(environment.ulogout, environment.creds);
  }

  /**
   * return logged in user
   * @returns {boolean}
   */
  checkAuth():Observable<IUser> {
    return this._apiService.get(environment.checkAuth);
  }

}
