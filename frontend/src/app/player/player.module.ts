import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {exportsPlayerCompoonents, playerCompoonents} from "./index";

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    ...playerCompoonents
  ],
  exports: [
    ...exportsPlayerCompoonents
  ]
})
export class PlayerModule { }
