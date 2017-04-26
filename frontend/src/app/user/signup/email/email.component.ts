import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";
import {AuthService} from "../../../shared/services/auth.service";
import {FormGroup, FormBuilder, Validators} from "@angular/forms";
import {User} from "./User";
import {HelpersService} from "../../../shared/services/helpers.service";
import {IMyOptions} from 'mydatepicker';
import {EmitterService} from "../../../shared/services/emitter.service";

@Component({
    selector: 'app-email',
    templateUrl: './email.component.html',
    styleUrls: ['./email.component.scss']
})
export class EmailComponent implements OnInit {
    signupEmailForm: FormGroup;
    user: User = new User();
    genderData = [
        {value: 'male', label: 'Male'},
        {value: 'female', label: 'Female'}
    ];

    constructor(private _userService: AuthService, private _router: Router, private helpers: HelpersService, private fb: FormBuilder) {

    }

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
            "required": "Required field."
        },
        "lname": {
            "required": "Required field."
        },
        "uname": {
            "required": "Required field."
        },
        "password": {
            "required": "Required field."
        },
        "email": {
            "required": "Required field."
        },
        "gender": {
            "required": "Required field."
        },
        "agree": {
            "required": "Required field."
        }
    };

    /**
     * создание формы
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
                Validators.required
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
     * валидация полей формы на лету
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
     * Регистрация пользователя
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

        this._userService.signUp(dataStr).subscribe(data => {
            EmitterService.get('LOGIN').emit(data);

            // сохраняем дание пользователя в localStorge
            localStorage.setItem('auth_data', JSON.stringify(data));

            EmitterService.get('TOGGLE_PRELOADER').emit(false);
            this._router.navigate([{outlets: {popup: null}}]);
        });
        return false;
    }

    /**
     * НАстройки DatePicker
     */
    public myDatePickerOptions: IMyOptions = {
        dateFormat: 'dd.mm.yyyy',
        editableDateField: false,
        openSelectorOnInputClick: true
    };

    /**
     * Установка дати в поле выбора Date of Birth
     */
    setDate(): void {
        let date = new Date();
        this.signupEmailForm.setValue({
            myDate: {
                date: {
                    year: date.getFullYear(),
                    month: date.getMonth() + 1,
                    day: date.getDate()
                }
            }
        });
    }

    /**
     * Очистка даты
     */
    clearDate(): void {
        // Clear the date using the setValue function
        this.signupEmailForm.setValue({myDate: ''});
    }

    /**
     *
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
