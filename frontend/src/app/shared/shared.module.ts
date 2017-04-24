import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {PreloaderComponent} from './index';
import {SharedService} from "./shared.service";
import {AudioPlayerComponent} from "./audio-player/audio-player.component";
import {pipes} from "../pipes/index";

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    ...pipes,
    PreloaderComponent,
    AudioPlayerComponent,
  ],
  exports: [
    PreloaderComponent, AudioPlayerComponent
  ],
  providers: [SharedService]
})
export class SharedModule {
}
