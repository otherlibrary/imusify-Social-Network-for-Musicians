import {ChangeDetectorRef, Component, Input, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {IRecord} from "../../interfases/IRecord";
import {SwiperConfigInterface, SwiperDirective} from "ngx-swiper-wrapper/dist";
import {PlayerService} from "../player.service";

@Component({
  selector: 'app-player-slider',
  templateUrl: './player-slider.component.html',
  styleUrls: ['./player-slider.component.scss']
})

export class PlayerBigComponent implements OnInit, OnDestroy {
  @Input() records: IRecord[];
  @Input() currentTrack: IRecord;
  @Input() isPlay: boolean;
  private _slideSub: any;
  public swiperConfig: SwiperConfigInterface = {
    slidesPerView: 7,
    centeredSlides: true,
    scrollbar: null
  };

  @ViewChild(SwiperDirective) swiperView: SwiperDirective;

  constructor(private _playerService: PlayerService, private ref: ChangeDetectorRef) { }

  getIndexInputRecord(record) {
    let index;
    this.records.map((item: IRecord, i) => {
      if(item.id === record.id) {
        index = i;
      }
    });
    return index;
  }

  ngOnInit() {
    this._slideSub = this._playerService.playerOutputSubject.subscribe((res: any) => {
      if(res.type === 'play') {
        this.goToSlideIndex(this.getIndexInputRecord(res.record));
      }
    });
  }

  ngOnDestroy() {
    this._slideSub.unsubscribe();
  }

  goToSlideIndex(index) {
    this.swiperView.swiper.slideTo(index);
  }

}
