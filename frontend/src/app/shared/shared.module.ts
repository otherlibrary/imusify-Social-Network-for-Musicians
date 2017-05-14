import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PreloaderComponent} from './index';
import {AudioPlayerComponent} from "./audio-player/audio-player.component";
import {directives, exportDirectives} from "../directives/index";
import {SwiperModule} from "angular2-useful-swiper";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {SelectModule} from "ng-select";
import {MyDatePickerModule} from "mydatepicker";
import {ToastyModule} from "ng2-toasty";

@NgModule({
  imports: [
    CommonModule,
    SwiperModule,
    FormsModule,
    ReactiveFormsModule,
    SelectModule,
    MyDatePickerModule,
    ToastyModule.forRoot()
  ],
  declarations: [
    ...directives,
    PreloaderComponent,
    AudioPlayerComponent
  ],
  exports: [
    PreloaderComponent,
    AudioPlayerComponent,
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    SelectModule,
    MyDatePickerModule,
    ToastyModule,
    ...exportDirectives
  ],
  providers: []
})
export class SharedModule {
}
