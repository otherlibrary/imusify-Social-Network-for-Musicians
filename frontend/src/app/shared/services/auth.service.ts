import { Injectable }     from '@angular/core';
import { Observable }     from 'rxjs/Rx';
import { Subject }        from "rxjs/Subject";
  
import { HelpersService } from './helpers.service';
import { AppConfig }      from '../../app.config';
import { IUser }          from "../../interfases/IUser";
import { ApiService }     from "./api.service";


@Injectable()
export class AuthService {
    public profileData: any;
    public loggedin: boolean;
    public redirectUrl: string;
    public cleanUserSubject: Subject<Object> = new Subject<Object>();

    constructor(
        private _apiService: ApiService,
        private _helpersService: HelpersService,
        private _appConfig: AppConfig
    ) {
        this.profileData = JSON.parse(localStorage.getItem('auth_data'));
        this.loggedin = this.profileData;
    }

    /**
     * Login
     * @param userData {username, password}
     * @returns {Observable<R>}
     */
    login(loginData) {
        // const params = this._helpersService.toStringParam(loginData);
        return this._apiService.post(this._appConfig.apiUrls.login, loginData);
    }

    /**
     *
     * @param newUser
     * @returns {Observable<R>}
     */
    signUp(newUser) {
        return this._apiService.post(this._appConfig.apiUrls.createUser, newUser);
    }

    /**
     * LOGOUT
     * @returns {Observable<R>}
     */
    logOut() {
        return this._apiService.post(this._appConfig.apiUrls.logout);
    }

    /**
     * return logged in user
     * @returns {boolean}
     */
    // checkAuth():Observable<IUser> {
    //     return this._apiService.get(environment.checkAuth);
    // }
}
