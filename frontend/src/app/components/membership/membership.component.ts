import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";

@Component({
    selector: 'app-membership',
    templateUrl: './membership.component.html',
    styleUrls: ['./membership.component.scss']
})
export class MembershipComponent implements OnInit {

    constructor(private _router: Router) {
    }

    ngOnInit() {
    }

    closePopup() {
        this._router.navigate([{outlets: {popup: null}}]);
    }
}
