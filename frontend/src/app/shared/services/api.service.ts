import { Injectable } from '@angular/core';
import {Http, URLSearchParams} from "@angular/http";
import {Observable} from "rxjs/Observable";
import {environment} from "../../../environments/environment";
import {contentHeaders} from "../../common/headers";

@Injectable()
export class ApiService {

  constructor(private http: Http) { }

  private formatErrors(error: any) {
    return Observable.throw(error.json());
  }

  get(path: string, params: URLSearchParams = new URLSearchParams()): Observable<any> {
    return this.http.get(`${environment.host}${path}`, {
      headers: contentHeaders,
      withCredentials: true,
      search: params
    })
      .catch(this.formatErrors)
      .map((res:Response) => res.json());
  }

  post(path: string, body: Object = {}): Observable<any> {
    return this.http.post(
      `${environment.host}${path}`,
      body,
      {
        headers: contentHeaders,
        withCredentials: true
      }
    )
      .catch(this.formatErrors)
      .map((res:Response) => res.json());
  }

}
