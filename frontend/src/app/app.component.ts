import {Component, HostBinding, OnDestroy, OnInit} from '@angular/core';
import {EmitterService} from "./shared/services/emitter.service";
import {AuthService} from "./shared/services/auth.service";
import {Router} from "@angular/router";
import {IToastOption} from "./interfases";
import {ToastData, ToastOptions, ToastyService} from "ng2-toasty";
import {SharedService} from "./shared/shared.service";
import {IUser} from "./interfases/IUser";

@Component({
  selector: 'body',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit, OnDestroy {
  @HostBinding('class') public cssClass = '';
  //popup
  public isVisible: boolean = false;

  //profile
  public loggedin: boolean;
  public profileData: any;

  private _cleanSubscriber: any;
  private _notificationSubscriber: any;
  private _loginSub: any;

  constructor(
    private _authService: AuthService,
    private _toastyService: ToastyService,
    private _sharedService: SharedService,
    private _router: Router) {
  }

  ngOnInit() {
    this.getProfile();
    EmitterService.get('TOGGLE_PRELOADER').subscribe((data: boolean) => {
      this.isVisible = data;
    });

    this._cleanSubscriber = this._authService.cleanUserSubject.subscribe(() => {
      this.cleanProfile();
    });

    this._authService.checkAuth().subscribe((user: IUser) => {
      if(!user.loggedin) {
        this._router.navigate([{outlets: {popup: 'about'}}]);
      }
    });

    this._notificationSubscriber = this._sharedService.notificationSubject.subscribe((option: IToastOption) => {
      this.addToast(option);
    });
  }

  ngOnDestroy() {
    this._cleanSubscriber.unsubscribe();
    this._notificationSubscriber.unsubscribe();
    this._loginSub.unsubscribe();
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
    localStorage.removeItem('sellData');
  }

  /**
   * Обработка данных пользователя
   */
  getProfile() {
    // обработчик если заходим первый раз через логин
    this._loginSub = this._sharedService.loginSubject.subscribe((data: any) => {
      this.profileData = this._authService.profileData = data;

      this.loggedin = this._authService.loggedin = data.loggedin;
    });

    // если пользователь уже залогинен вытягиваем даные из сервиса
    this.profileData = this._authService.profileData;
    this.loggedin = this._authService.loggedin;
    //this._router.navigate([{outlets: {popup: 'about'}}]);

    //обработчик если пользователь разлогинен каким либо другим образом кроме LOGOUT
    EmitterService.get('LOGOUT').subscribe(data => {
      this.cleanProfile();
    });
  }

  addToast(option: IToastOption) {
    let toastOptions: ToastOptions = {
      title: option.title,
      msg: option.msg,
      showClose: true,
      timeout: 6000,
      theme: 'material',
      onAdd: (toast: ToastData) => {
        console.log('Toast ' + toast.id + ' has been added!');
      },
      onRemove: function (toast: ToastData) {
        console.log('Toast ' + toast.id + ' has been removed!');
      }
    };
    switch (option.type) {
      case 'default':
        this._toastyService.default(toastOptions);
        break;
      case 'info':
        this._toastyService.info(toastOptions);
        break;
      case 'success':
        this._toastyService.success(toastOptions);
        break;
      case 'wait':
        this._toastyService.wait(toastOptions);
        break;
      case 'error':
        this._toastyService.error(toastOptions);
        break;
      case 'warning':
        this._toastyService.warning(toastOptions);
        break;
    }
  }

}
