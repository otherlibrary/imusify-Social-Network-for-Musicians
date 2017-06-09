import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {playlistComp} from "./";
import {SharedModule} from "../shared/shared.module";
import {SwiperModule} from "ngx-swiper-wrapper";

@NgModule({
  imports: [
    CommonModule,
    SharedModule,
    SwiperModule
  ],
  declarations: [
    ...playlistComp
  ],
  exports: [
    ...playlistComp
  ]
})
export class PlaylistModule { }
