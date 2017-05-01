import { Injectable } from '@angular/core';
import {Http, RequestOptionsArgs} from "@angular/http";


@Injectable()
export class HttpClientService {

  constructor(private http: Http) { }

  get(url: string, options?: RequestOptionsArgs) {
    //TODO(AlexSol): add request to User login
    // this.http.get
    return this.http.get(url, options);
  }

  post(url: string, body: any, options?: RequestOptionsArgs) {
    return this.http.post(url, body, options);
  }
}
