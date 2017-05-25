import {Component, OnInit, ViewChild} from '@angular/core';
import {Observable} from "rxjs";
import {Router} from "@angular/router";
import {Playlist} from "../../interfases/playlist/Playlist";
import {SwiperConfigInterface, SwiperDirective} from "ngx-swiper-wrapper";

@Component({
  selector: 'app-playlist',
  templateUrl: './playlist.component.html',
  styleUrls: ['./playlist.component.scss']
})
export class PlaylistComponent implements OnInit {
  public isShow: boolean = false;

  public swiperConfig: SwiperConfigInterface = {
    slidesPerView: 2,
    centeredSlides: true,
    spaceBetween: 0,
    initialSlide: 1,
    nextButton: '.wrap-playlist .swiper-button-next',
    prevButton: '.wrap-playlist .swiper-button-prev',
  };
  @ViewChild(SwiperDirective) swiperView: SwiperDirective;

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