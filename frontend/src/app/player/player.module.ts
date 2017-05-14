import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {exportsPlayerComponents, playerComponents} from "./index";
import {TimeFormatPipe} from "./pipes/time-format.pipe";
import {AudioSliderDirective} from "../directives/audio-slider.directive";

@NgModule({
  imports: [
    CommonModule
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
