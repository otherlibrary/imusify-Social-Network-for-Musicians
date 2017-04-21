import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";

@Component({
    selector: 'app-signup',
    templateUrl: './signup.component.html',
    styleUrls: ['./signup.component.scss']
})
export class SignupComponent implements OnInit {

    constructor(private _router: Router) {

    }

    ngOnInit() {
    }

    closePopup() {
        this._router.navigate([{outlets: {popup: null}}]);
    }

    goTologin() {
        this._router.navigate([{outlets: {popup: 'login'}}]);
        return false;
    }

    openEmail() {
        this._router.navigate([{outlets: {popup: 'signup/email'}}]);
        return false;
    }
}
