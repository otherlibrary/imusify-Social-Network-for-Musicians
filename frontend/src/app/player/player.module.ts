import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {exportsPlayerComponents, playerComponents} from "./index";
import {TimeFormatPipe} from "./pipes/time-format.pipe";
import {AudioSliderDirective} from "../directives/audio-slider.directive";
import {SwiperModule} from "ngx-swiper-wrapper";

@NgModule({
  imports: [
    CommonModule,
    SwiperModule
  ],
  declarations: [
    ...playerComponents,
    TimeFormatPipe,
    AudioSliderDirective
  ],
  exports: [
    ...exportsPlayerComponents
  ]
})
export class PlayerModule { }
