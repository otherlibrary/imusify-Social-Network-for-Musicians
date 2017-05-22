import { Injectable } from '@angular/core';
import {ActivatedRouteSnapshot, Resolve, RouterStateSnapshot} from "@angular/router";
import {HomeService} from "./home.service";
import {Observable} from "rxjs/Observable";
import {ITracksData} from "../interfases";

@Injectable()
export class HomeResolverService implements Resolve<ITracksData> {

  constructor(
    private _homeService: HomeService
  ) {}

  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<ITracksData> {
    return this._homeService.getAllNews();
  }

}
