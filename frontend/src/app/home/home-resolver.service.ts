import { Injectable } from '@angular/core';
import {RouterStateSnapshot} from "@angular/router";
import {HomeService} from "./home.service";
import {Observable} from "rxjs/Observable";
import {ITracksData} from "../interfases/ITracksData";

@Injectable()
export class HomeResolverService {

  constructor(
    private homeService: HomeService
  ) {}

  resolve(
    state: RouterStateSnapshot
  ): Observable<ITracksData> {
    return this.homeService.getAllNews();
  }

}
