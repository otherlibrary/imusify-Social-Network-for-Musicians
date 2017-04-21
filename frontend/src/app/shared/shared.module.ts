import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {PreloaderComponent} from './index';
import {SharedService} from "./shared.service";
import {AudioPlayerComponent} from "./audio-player/audio-player.component";

@NgModule({
    imports: [
        CommonModule
    ],
    declarations: [PreloaderComponent, AudioPlayerComponent],
    exports: [
        PreloaderComponent, AudioPlayerComponent
    ],
    providers: [SharedService]
})
export class SharedModule {
}
