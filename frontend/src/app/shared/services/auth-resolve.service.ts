import { Injectable } from '@angular/core';
import {IUser} from "../../interfases/IUser";
import {ActivatedRouteSnapshot, Resolve} from "@angular/router";
import {AuthService} from "./auth.service";

@Injectable()
export class AuthResolveService implements Resolve<IUser> {

  constructor(
    private _AuthService: AuthService
  ) {}

  resolve(route: ActivatedRouteSnapshot): any | boolean {
    return this._AuthService.getUser().map((user: IUser) => {
      if (+user.user_id !== 0) {
        return user;
      } else {
        this._AuthService.cleanUserSubject.next();
        return false;
      }
    });
  }

}
