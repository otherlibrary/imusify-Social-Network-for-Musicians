import {Injectable} from '@angular/core';
import {CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, CanActivateChild}    from '@angular/router';
import {AuthService} from "../shared/services/auth.service";
import {Observable} from "rxjs/Observable";
import {IUser} from "../interfases/IUser";

@Injectable()
export class AuthAllSuccessGuard implements CanActivateChild {

  constructor(private _authService: AuthService) {
  }

  canActivateChild(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
    return this._authService.checkAuth().map((user: IUser) => {
      if (user.loggedin) {
        return true;
      } else {
        this._authService.cleanUserSubject.next();
        return true;
      }
    }).catch(() => {
      return Observable.of(true);
    });
  }
}