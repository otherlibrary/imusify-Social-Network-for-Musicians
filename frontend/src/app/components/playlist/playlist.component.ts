import {Component, OnInit} from '@angular/core';
import {Observable} from "rxjs";
import {Router} from "@angular/router";

@Component({
    selector: 'app-playlist',
    templateUrl: './playlist.component.html',
    styleUrls: ['./playlist.component.scss']
})
export class PlaylistComponent implements OnInit {
    public isShow: boolean = false;

    constructor(private _router: Router) {
    }

    ngOnInit() {
        this.getTracks();
    }

    //TODO:AlexSol запитна плейліст
    getTracks() {
        this.server().subscribe(() => {
            this.isShow = true;
        })
    }

    server() {
        return new Observable(observable => {
            setTimeout(function () {
                observable.next(123)
            }, 500);
        })
    }

    closePlaylist() {
        this.isShow = false;
        this._router.navigate([{outlets: {popup: null}}]);
    }
}
