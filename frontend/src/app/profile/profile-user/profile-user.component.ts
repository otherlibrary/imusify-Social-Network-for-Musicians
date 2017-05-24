import {Component, Input, OnInit, ViewChild} from '@angular/core';
import {IProfile} from "../../interfases/profile/IProfile";
import {SwiperConfigInterface, SwiperDirective} from "ngx-swiper-wrapper";


@Component({
  selector: 'app-profile-user',
  templateUrl: './profile-user.component.html',
  styleUrls: ['./profile-user.component.scss']
})
export class ProfileUserComponent implements OnInit {
  @Input() profile: IProfile;


  @ViewChild(SwiperDirective) swiperView: SwiperDirective;

  public swiperConfig: SwiperConfigInterface = {
    slidesPerView: 3,
    centeredSlides: true,
    spaceBetween: 0,
    nextButton: '.top-playlist .swiper-button-next',
    prevButton: '.top-playlist .swiper-button-prev',
    scrollbar: null
  };

  constructor() {
  }

  ngOnInit() {

  }

  goToSlideIndex(index) {
    this.swiperView.swiper.slideTo(index);
  }



}
