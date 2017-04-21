import {Component, OnInit} from '@angular/core';
import {AuthService} from "../../shared/services/auth.service";
import {Router} from '@angular/router';
import {FormGroup, FormBuilder, Validators} from '@angular/forms';
import {User} from './User'
import {EmitterService} from "../../shared/services/emitter.service";


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

    constructor(private _authService: AuthService,
                private _router: Router,
                private fb: FormBuilder,
                private _emitterService: EmitterService) {
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

    // TODO опрацювати помилки по всьому сайту
    login() {
        EmitterService.get('TOGGLE_PRELOADER').emit(true);

        this._authService.login(this.userLoginForm.value).subscribe(data => {
            // отправляем данные пользователя в апп компонент для того что б не перезагружать страницу
            EmitterService.get('LOGIN').emit(data);

            // сохраняем дание пользователя в localStorge
            localStorage.setItem('auth_data', JSON.stringify(data));

            //закриваем окно авторизации
            this._router.navigate([{outlets: {popup: null}}]);

            EmitterService.get('TOGGLE_PRELOADER').emit(false);
        }, err => {
            EmitterService.get('TOGGLE_PRELOADER').emit(false);
            console.log(err);
        })
    }

    closePopup() {
        this._router.navigate([{outlets: {popup: null}}]);
    }

    goToSignup() {
        this._router.navigate([{outlets: {popup: 'signup'}}]);
        return false;
    }
}
