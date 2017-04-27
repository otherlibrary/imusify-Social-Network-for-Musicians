import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";

@Component({
    selector: 'app-browse',
    templateUrl: './browse.component.html',
    styleUrls: ['./browse.component.scss']
})
export class BrowseComponent implements OnInit {
    constructor(private _router: Router) {}

    ngOnInit() {}

    goToSearch() {
        this._router.navigate([{outlets: {popup: 'search'}}]);
        return false;
    }

}
