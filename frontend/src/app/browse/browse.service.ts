import { Injectable } from '@angular/core';
import {contentHeaders} from "../common/headers";
import {Observable} from "rxjs/Observable";
import {Http} from "@angular/http";
import {environment} from "../../environments/environment";

@Injectable()
export class BrowseService {

  public host: string;

  constructor(private _http: Http) {
    this.host = environment.host;
  }


  getPopularRecords() {
    const creds = 'ajax=true';
    return this._http.post(this.host + '/browse', creds, {
      withCredentials: true,
      headers: contentHeaders
    })
      .map((res) => res.json())
      .catch((error: any) => Observable.throw(error.json().error || 'Server error'));
  }
}
