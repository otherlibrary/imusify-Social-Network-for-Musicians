import {Component, OnInit} from '@angular/core';
import {Observable} from "rxjs";
import {Router} from "@angular/router";

@Component({
    selector: 'app-favorites',
    templateUrl: './favorites.component.html',
    styleUrls: ['./favorites.component.scss']
})
export class FavoritesComponent implements OnInit {
    public isShow: boolean = false;

    constructor(private _router: Router) {
    }

    ngOnInit() {
        this.getTracks();
    }

    //TODO:AlexSol запитна favorites
    getTracks() {
        this.server().subscribe(() => {
            this.isShow = true;
        })
    }

    server() {
        return new Observable(observable => {
            setTimeout(function () {
                observable.next(123)
            }, 1000);
        })
    }

    closeFavorites() {
        this.isShow = false;
        this._router.navigate([{outlets: {popup: null}}]);
    }

}
