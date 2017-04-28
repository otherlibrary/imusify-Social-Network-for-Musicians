import {Injectable, Inject} from '@angular/core';
import {Http, Response} from '@angular/http';
import {contentHeaders} from '../common/headers';
import {HelpersService} from '../shared/services/helpers.service';
import {environment} from '../../environments/environment';
import {Observable} from 'rxjs';

@Injectable()
export class HomeService {
    public host: string;

    constructor(private _http: Http, private _helpersService: HelpersService) {
        this.host = environment.host;
    }

    /**
     * дані записів
     * @returns {Observable<R>}
     */
    getAllNews() {
        return this._http.post(this.host, environment.creds, {
            headers: contentHeaders,
            withCredentials: true
        })
            .map((res: Response) => res.json())
            .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
    }

    /**
     * follow link
     * @param data
     * @returns {Observable<R>}
     */
    addFollow(data) {
        const str = this._helpersService.toStringParam(data);
        return this._http.post(this.host + '/follow', str, {
            headers: contentHeaders,
            withCredentials: true
        })
            .map((res: Response) => res.json())
            .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
    }
}
