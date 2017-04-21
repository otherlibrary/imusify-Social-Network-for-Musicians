import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";
import {Observable} from "rxjs";

@Component({
    selector: 'app-notifications',
    templateUrl: './notifications.component.html',
    styleUrls: ['./notifications.component.scss']
})
export class NotificationsComponent implements OnInit {
    public isShow: boolean = false;

    constructor(private _router: Router) {
    }

    ngOnInit() {
        this.getNotifications();
    }

    getNotifications() {
        this.server().subscribe(() => {
            this.isShow = true;
        })
    }

    server() {
        return new Observable(observable => {
            setTimeout(function () {
                observable.next([{"name": "test"}])
            }, 500);
        })
    }

    closeNotification() {
        this.isShow = false;
        this._router.navigate([{outlets: {popup: null}}]);
    }

}
