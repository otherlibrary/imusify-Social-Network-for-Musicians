import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";

@Component({
    selector: 'app-invite-friends',
    templateUrl: './invite-friends.component.html',
    styleUrls: ['./invite-friends.component.scss']
})
export class InviteFriendsComponent implements OnInit {

    constructor(private _router: Router) {
    }

    ngOnInit() {
    }

    closePopup() {
        this._router.navigate([{outlets: {popup: null}}]);
    }
}
