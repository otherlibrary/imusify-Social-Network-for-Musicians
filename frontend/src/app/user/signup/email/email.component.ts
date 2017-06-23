import { Component, OnInit }                  from '@angular/core';
import { Router }                             from "@angular/router";
import { FormGroup, FormBuilder, Validators } from "@angular/forms";

import { IMyOptions }                         from 'mydatepicker';

import { AuthService }                        from "../../../shared/services/auth.service";
import { HelpersService }                     from "../../../shared/services/helpers.service";
import { EmitterService }                     from "../../../shared/services/emitter.service";
import { SharedService }                      from "../../../shared/shared.service";
import { AppConfig }                          from '../../../app.config';

import { User }                               from "./User";

@Component({
    selector: 'app-email',
    templateUrl: './email.component.html',
    styleUrls: ['./email.component.scss']
})
export class EmailComponent implements OnInit {

    signupEmailForm: FormGroup;
    user: User = new User();
    genderData = this._appConfig.gender;

    // Date Picker settings
    public myDatePickerOptions: IMyOptions = {
        dateFormat: 'dd.mm.yyyy',
        editableDateField: false,
        openSelectorOnInputClick: true
    };

    constructor(
        private _userService: AuthService,
        private _router: Router,
        private _sharedService: SharedService,
        private _appConfig: AppConfig,
        private fb: FormBuilder,
        private helpers: HelpersService,
    ) {}

    ngOnInit() {
        this.buildForm();
    }

    formErrors = {
        "fname": "",
        "lname": "",
        "uname": "",
        "password": "",
        "email": "",
        "gender": "",
        "agree": ""
    };

    validationMessages = {
        "fname": {
            "required": this._appConfig.errorMessages.first_name
        },
        "lname": {
            "required": this._appConfig.errorMessages.last_name
        },
        "uname": {
            "required": this._appConfig.errorMessages.username
        },
        "password": {
            "required":  this._appConfig.errorMessages.password,
            "minLength": this._appConfig.errorMessages.password,
        },
        "email": {
            "required": this._appConfig.errorMessages.email
        },
        "gender": {
            "required": this._appConfig.errorMessages.gender
        },
        "agree": {
            "required": this._appConfig.errorMessages.agree
        }
    };

    /**
     * Create a form
     */
    buildForm() {
        this.signupEmailForm = this.fb.group({
            "fname": [this.user.fname, [
                Validators.required
            ]],
            "lname": [this.user.lname, [
                Validators.required
            ]],
            "uname": [this.user.uname, [
                Validators.required
            ]],
            "password": [this.user.password, [
                Validators.required,
                Validators.minLength(4),
            ]],
            "email": [this.user.email, [
                Validators.required,
                Validators.pattern("[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}")
            ]],
            "gender": [this.user.gender, [
                Validators.required
            ]],
            "agree": [this.user.agree, [
                Validators.required,
                Validators.pattern("true")
            ]]
        });
        this.signupEmailForm.valueChanges
            .subscribe(data => this.onValueChange(data));

        this.onValueChange();
    }

    /**
     * Form validation during changing a values of a form
     * @param data
     */
    onValueChange(data?: any) {
        if (!this.signupEmailForm) return;
        let form = this.signupEmailForm;

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

    /**
     * Form submit
     * @returns {boolean}
     */
    signUp() {
        let data = {};
        EmitterService.get('TOGGLE_PRELOADER').emit(true);

        for (let key in this.signupEmailForm.value) {
            if (this.signupEmailForm.value.hasOwnProperty(key)) {
                if (key == 'myDate') {
                    data['dd'] = this.signupEmailForm.value[key].date.day;
                    data['mm'] = this.signupEmailForm.value[key].date.month;
                    data['yy'] = this.signupEmailForm.value[key].date.year;
                    continue;
                }
                data[key] = this.signupEmailForm.value[key];
            }
        }

        let dataStr = this.helpers.toStringParam(data);
        this._userService.signUp(dataStr).subscribe(
            data => {

                this._sharedService.loginSubject.next(data);
                // сохраняем дание пользователя в localStorge
                localStorage.setItem('auth_data', JSON.stringify(data));

                EmitterService.get('TOGGLE_PRELOADER').emit(false);
                this._router.navigate([{outlets: {popup: 'roles'}}]);


                this._sharedService.notificationSubject.next({
                    title: 'Sign up',
                    msg:   'sign up success',
                    type:  'success'
                });
            }, 
            err => {

                EmitterService.get('TOGGLE_PRELOADER').emit(false);
                this._sharedService.notificationSubject.next({
                    title: 'Sign up',
                    msg:   err.error,
                    type:  'error'
                });
            });
        return false;
    }

    /**
     * Set a date in the 'Birthday' field
     */
    setDate(): void {
        let date = new Date();
        this.signupEmailForm.setValue({
            myDate: {
                date: {
                    year:  date.getFullYear(),
                    month: date.getMonth() + 1,
                    day:   date.getDate()
                }
            }
        });
    }

    /**
     * Clear date
     */
    clearDate(): void {
        // Clear the date using the setValue function
        this.signupEmailForm.setValue({myDate: ''});
    }

    /**
     * Close popup
     * @returns {boolean}
     */
    closePopup() {
        this._router.navigate([{outlets: {popup: null}}]);

        return false;
    }

    goToSignup() {
        this._router.navigate([{outlets: {popup: 'signup'}}]);
        return false;
    }

    goToLogin() {
        this._router.navigate([{outlets: {popup: 'login'}}]);
        return false;
    }

}
