import { Injectable } from '@angular/core';
import {ActivatedRouteSnapshot, Resolve, RouterStateSnapshot} from "@angular/router";
import {HomeService} from "./home.service";
import {Observable} from "rxjs/Observable";
import {ITracksData} from "../interfases";
import {EmitterService} from "../shared/services/emitter.service";

@Injectable()
export class HomeResolverService implements Resolve<ITracksData> {

  constructor(
    private _homeService: HomeService
  ) {}

  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<ITracksData> {
    EmitterService.get('TOGGLE_PRELOADER').emit(true);
    return this._homeService.getAllNews();
  }

}
