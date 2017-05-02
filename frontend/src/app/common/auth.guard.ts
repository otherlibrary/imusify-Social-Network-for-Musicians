import {Injectable} from '@angular/core';
import {Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot}    from '@angular/router';
import {AuthService} from "../shared/services/auth.service";
import {Observable} from "rxjs/Observable";
import {IUser} from "../interfases/IUser";

@Injectable()
export class AuthGuard implements CanActivate {

    constructor(private _router: Router, private _authService: AuthService) {
    }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
      return this._authService.checkAuth().map((user: IUser) => {
        if (user.loggedin) {
          return true;
        } else {
          this._authService.redirectUrl = state.url;
          this._router.navigate([{outlets: {popup: 'login'}}]);
          this._authService.cleanUserSubject.next();
          return Observable.of(false);
        }
      }).catch(() => {
        return Observable.of(false);
      });
    }
}
