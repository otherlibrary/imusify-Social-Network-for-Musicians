import {Injectable} from '@angular/core';
import {Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot}    from '@angular/router';
import {AuthService} from "../shared/services/auth.service";

@Injectable()
export class AuthGuard implements CanActivate {

    constructor(private _router: Router, private _authService: AuthService) {
    }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {

        if (this._authService.isLoggedIn()) {
            return true;
        } else {
            this._authService.redirectUrl = state.url;
            this._router.navigate([{outlets: {popup: 'login'}}]);
            return false;
        }
    }
}
