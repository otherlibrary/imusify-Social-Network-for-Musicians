import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";

@Component({
    selector: 'app-roles',
    templateUrl: './roles.component.html',
    styleUrls: ['./roles.component.scss']
})
export class RolesComponent implements OnInit {

    constructor(private _router: Router) {
    }

    ngOnInit() {
    }

    closePopup() {
        this._router.navigate([{outlets: {popup: null}}]);
    }
}
