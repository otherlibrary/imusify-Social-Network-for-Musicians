import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {exportsPlayerComponents, playerComponents} from "./index";
import {TimeFormatPipe} from "./pipes/time-format.pipe";
import {AudioSliderDirective} from "../directives/audio-slider.directive";
import { PlayPauseComponent } from './play-pause/play-pause.component';

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
