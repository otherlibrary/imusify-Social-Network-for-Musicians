import {Component, OnInit} from '@angular/core';
import {Observable} from "rxjs";
import {Router} from "@angular/router";
import {Playlist} from "../../interfases/playlist/Playlist";

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
