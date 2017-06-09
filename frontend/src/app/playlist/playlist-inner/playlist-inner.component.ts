import {Component, NgZone, OnInit, ViewChild} from '@angular/core';
import {SwiperConfigInterface, SwiperDirective} from "ngx-swiper-wrapper";
import {IPlaylists, Playlist} from "../../interfases/playlist/Playlist";
import {Router} from "@angular/router";
import {Observable} from "rxjs/Observable";


@Component({
  selector: 'app-playlist-inner',
  templateUrl: './playlist-inner.component.html',
  styleUrls: ['./playlist-inner.component.scss']
})


export class PlaylistInnerComponent implements OnInit {

  public isShow: boolean = false;
  public playlistData: IPlaylists;
  public playlist: Playlist[];
  public currentPlaylist: Playlist;
  public current: number;

  public swiperConfig: SwiperConfigInterface = {
    slidesPerView: 2,
    centeredSlides: true,
    spaceBetween: 0,
    initialSlide: 1,
    nextButton: '.wrap-playlist .swiper-button-next',
    prevButton: '.wrap-playlist .swiper-button-prev',
    onSlideChangeEnd: (slider) => {
      this.zone.run(() => {
        this.current = slider.activeIndex;
        this.currentPlaylist = this.playlist[this.current - 1];
        console.log(this.currentPlaylist);
      });
    }
  };

  @ViewChild(SwiperDirective) swiperView: SwiperDirective;

  constructor(private _router: Router,
              public zone: NgZone) {
  }

  ngOnInit() {
    this.getTracks();

  }

  getTracks() {
    this.server().subscribe((res: IPlaylists) => {
      this.playlistData = res;
      this.playlist = res.playlist;
      this.isShow = true;
    })
  }

  server() {
    let playlistData = {
      currentId: '2',
      loggedin: true,
      playlist: []
    };
    for (let i = 0; i < 10; i++) {
      playlistData.playlist.push(new Playlist(
        '' + i, '10' + i, '20.10.2010', '212'+i, 'cool play list ' + i, '/assets/images/profile/vinil-face1.jpg', '21', '223', 'y', '10.12.2016',
        '2', '2121', '21'
      ));
    }
    return new Observable(observable => {
      setTimeout(function () {
        observable.next(playlistData)
      }, 500);
    })
  }

  closePlaylist() {
    this.isShow = false;
    this._router.navigate([{outlets: {popup: null}}]);
  }

  goToSlideIndex(index) {
    this.swiperView.swiper.slideTo(index);
  }

  onIndexChange(e) {
    console.log(32323232);
  }

}
