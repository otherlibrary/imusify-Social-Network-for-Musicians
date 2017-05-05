import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {PreloaderComponent} from './index';
import {AudioPlayerComponent} from "./audio-player/audio-player.component";
import {pipes} from "../pipes/index";
import {directives, exportDirectives} from "../directives/index";
import {SwiperModule} from "angular2-useful-swiper";

@NgModule({
  imports: [
    CommonModule,
    SwiperModule
  ],
  declarations: [
    ...pipes,
    ...directives,
    PreloaderComponent,
    AudioPlayerComponent,
  ],
  exports: [
    PreloaderComponent,
    AudioPlayerComponent,
    ...exportDirectives
  ],
  providers: []
})
export class SharedModule {
}
