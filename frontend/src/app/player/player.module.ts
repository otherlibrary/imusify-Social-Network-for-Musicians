import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {exportsPlayerCompoonents, playerCompoonents} from "./index";
import {TimeFormatPipe} from "./pipes/time-format.pipe";

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    ...playerCompoonents,
    TimeFormatPipe
  ],
  exports: [
    ...exportsPlayerCompoonents
  ]
})
export class PlayerModule { }
