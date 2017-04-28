import {Injectable} from '@angular/core';
import {contentHeaders} from '../../common/headers';
import {Http, Response} from '@angular/http';
import {Observable} from 'rxjs/Rx';
import {HelpersService} from './helpers.service';
import {environment} from '../../../environments/environment';


@Injectable()
export class AuthService {
    public host: Object = {};
    public profileData: any;
    public loggedin: boolean;
    public redirectUrl: string;

    constructor(private _http: Http, private _helpersService: HelpersService) {
        this.host = environment.host;
        console.log('auth service');
        // получаэм данные юзера из localStorage
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
        return this._http.post(this.host + '/api/login', params, {
            headers: contentHeaders,
            withCredentials: true
        })
            .map((res: Response) => res.json())
            .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
    }

    /**
     *
     * @param newUser
     * @returns {Observable<R>}
     */
    signUp(newUser) {
        return this._http.post(this.host + '/api/signup', newUser, {
            headers: contentHeaders,
            withCredentials: true
        })
            .map((res: Response) => res.json())
            .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
    }

    /**
     * LOGOUT
     * @returns {Observable<R>}
     */
    logOut() {
        return this._http.post(this.host + '/ulogout', environment.creds, {
            headers: contentHeaders,
            withCredentials: true
        })
            .map((res: Response) => res.json())
            .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
    }

    /**
     * return loggedin user
     * @returns {boolean}
     */
    isLoggedIn() {
        return this.loggedin;
    }
}
