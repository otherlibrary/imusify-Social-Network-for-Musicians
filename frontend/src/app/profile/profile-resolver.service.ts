import { Injectable } from '@angular/core';
import {ProfileService} from "./profile.service";
import {ActivatedRouteSnapshot, Resolve, RouterStateSnapshot} from "@angular/router";
import {Observable} from "rxjs/Observable";
import {IProfile} from "../interfases";
import {EmitterService} from "../shared/services/emitter.service";

@Injectable()
export class ProfileResolverService implements Resolve<IProfile> {

  constructor(private _profileService: ProfileService) {}

  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<IProfile> {
    let id = route.params["id"];
    EmitterService.get('TOGGLE_PRELOADER').emit(true);
    return this._profileService.getProfileData(id);
  }
}
