import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PreloaderComponent} from './index';
import {directives, exportDirectives} from "../directives/index";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {SelectModule} from "ng-select";
import {MyDatePickerModule} from "mydatepicker";
import {ToastyModule} from "ng2-toasty";
import { SwiperModule } from 'ngx-swiper-wrapper';
import { SwiperConfigInterface } from 'ngx-swiper-wrapper';
import {PlayerModule} from "../player/player.module";

@NgModule({
  imports: [
    CommonModule,
    SwiperModule,
    FormsModule,
    ReactiveFormsModule,
    SelectModule,
    MyDatePickerModule,
    ToastyModule.forRoot(),
    PlayerModule
  ],
  declarations: [
    ...directives,
    PreloaderComponent
  ],
  exports: [
    PreloaderComponent,
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    SelectModule,
    MyDatePickerModule,
    ToastyModule,
    SwiperModule,
    PlayerModule,
    ...exportDirectives
  ],
  providers: []
})
export class SharedModule {
}
