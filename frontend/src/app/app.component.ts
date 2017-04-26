import {Component, HostBinding} from '@angular/core';
import {EmitterService} from "./shared/services/emitter.service";
import {AuthService} from "./shared/services/auth.service";
import {Router} from "@angular/router";

@Component({
    selector: 'body',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.scss']
})
export class AppComponent {
    @HostBinding('class') public cssClass = '';
    //popup
    public isVisible: boolean = false;

    //profile
    public loggedin: boolean;
    public profileData: any;


    constructor(private _authService: AuthService, private _router: Router) {
    }

    /**
     *
     * @returns {boolean}
     */
    logOut() {
        EmitterService.get('TOGGLE_PRELOADER').emit(true);
        this._authService.logOut().subscribe((data) => {
            this.cleanProfile();
            EmitterService.get('TOGGLE_PRELOADER').emit(false);
            this._router.navigate(['/home']);
        });
        return false;
    }

    /**
     * Очищает даные пользователя на клиенте
     */
    cleanProfile() {
        this.profileData = this._authService.profileData = null;
        this.loggedin = this._authService.loggedin = false;
        localStorage.removeItem('auth_data');
    }

    /**
     * Обработка данных пользователя
     */
    getProfile() {
        // обработчик если заходим первый раз через логин
        EmitterService.get('LOGIN').subscribe(data => {
            //todo заменить на обьект юзера
            this.profileData = this._authService.profileData = data;
            this.loggedin = this._authService.loggedin = data.loggedin;
        });

        // если пользователь уже залогинен вытягиваем даные из сервиса
        this.profileData = this._authService.profileData;
        this.loggedin = this._authService.loggedin;

        //обработчик если пользователь разлогинен каким либо другим образом кроме LOGOUT
        EmitterService.get('LOGOUT').subscribe(data => {
            this.cleanProfile();
        });
    }

    ngOnInit() {
        this.getProfile();
        EmitterService.get('TOGGLE_PRELOADER').subscribe((data: boolean) => {
            this.isVisible = data;
        })
    }
}
