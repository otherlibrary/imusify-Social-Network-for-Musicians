import {Component, OnInit, ViewChild} from '@angular/core';
import {SwiperConfigInterface, SwiperDirective} from "ngx-swiper-wrapper";

@Component({
  selector: 'app-browse-recommended',
  templateUrl: './browse-recommended.component.html',
  styleUrls: ['./browse-recommended.component.scss']
})
export class BrowseRecommendedComponent implements OnInit {
  public swiperConfig: SwiperConfigInterface = {
    pagination: '.peoples-wrap .swiper-pagination',
    slidesPerView: 4,
    centeredSlides: true,
    paginationClickable: true,
    spaceBetween: 10,
    nextButton: '.browse-top .swiper-button-next',
    prevButton: '.browse-top .swiper-button-prev',
    onSlideChangeEnd: (slider) => {
      console.log(slider.activeIndex);
    }
  };

  @ViewChild(SwiperDirective) swiperView: SwiperDirective;

  constructor() {
  }

  ngOnInit() {
  }

}
