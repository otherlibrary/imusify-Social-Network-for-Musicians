import { Component, OnInit }                  from '@angular/core';
import { Router }                             from '@angular/router';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FacebookService, LoginResponse }     from "ngx-facebook";

import { User }                               from './User';

import { AuthService }                        from "../../shared/services/auth.service";
import { EmitterService }                     from "../../shared/services/emitter.service";
import { SharedService }                      from "../../shared/shared.service";
declare const IN;

@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

    isVisible: boolean = false;
    userLoginForm: FormGroup;
    user: User = new User();

    formErrors = {
        "username": "",
        "password": ""
    };
    validationMessages = {
        "username": {
            "required": "Required field."
        },
        "password": {
            "required": "Required field."
        }
    };

    username: string;
    password: string;
    rememberme: any;

    constructor(
        private _authService: AuthService,
        private _router: Router,
        private fb: FormBuilder,
        private _sharedService: SharedService,
        private _facebookService: FacebookService) {
    }

    ngOnInit() {
        this.buildForm();
    }

    buildForm() {
        this.userLoginForm = this.fb.group({
            "username": [this.user.username, [
                Validators.required
            ]],
            "password": [this.user.password, [
                Validators.required
            ]],
            "rememberme": [this.user.rememberme, []]
        });
        this.userLoginForm.valueChanges
            .subscribe(data => this.onValueChange(data));

        this.onValueChange();
    }

    onValueChange(data?: any) {
        if (!this.userLoginForm) return;
        let form = this.userLoginForm;

        for (let field in this.formErrors) {
            this.formErrors[field] = "";
            // form.get - получение элемента управления
            let control = form.get(field);

            if (control && control.dirty && !control.valid) {
                let message = this.validationMessages[field];
                for (let key in control.errors) {
                    this.formErrors[field] += message[key] + " ";
                }
            }
        }
    }

    login() {
        EmitterService.get('TOGGLE_PRELOADER').emit(true);

        this._authService.login(this.userLoginForm.value).subscribe(
            data => {
                this._sharedService.loginSubject.next(data);
                localStorage.setItem('auth_data', JSON.stringify(data));
                this._router.navigate([{outlets: {popup: null}}]);

                EmitterService.get('TOGGLE_PRELOADER').emit(false);
                this._sharedService.notificationSubject.next({
                    title: 'Sign in',
                    msg: 'success',
                    type: 'success'
                });
            }, 
            err => {
                EmitterService.get('TOGGLE_PRELOADER').emit(false);
                this._sharedService.notificationSubject.next({
                    title: 'Sign in',
                    msg: err.error,
                    type: 'error'
                });
            })
    }

    loginFB() {
        this._facebookService.login()
            .then((response: LoginResponse) => console.log('Logged in', response))
            .catch(e => console.error('Error logging in'));
    }

    loginLD() {
        IN.User.authorize(function(){
            IN.API.Raw().url('/people/~?format=json').method('GET').result(function(res){
                console.log(res);
            });
        }, this);
    }

    closePopup() {
        this._router.navigate(['',{outlets: {popup: null}}]);
    }

    goToSignup() {
        this._router.navigate([{ outlets: { popup: 'signup' } }]);
        return false;
    }
}
