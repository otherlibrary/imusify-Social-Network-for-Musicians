import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {playlistComp} from "./";

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    ...playlistComp
  ],
  exports: [
    ...playlistComp
  ]
})
export class PlaylistModule { }
