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

const SWIPER_CONFIG: SwiperConfigInterface = {
  direction: 'horizontal',
  slidesPerView: 'auto',
  keyboardControl: true
};

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
    ...exportDirectives
  ],
  providers: []
})
export class SharedModule {
}
