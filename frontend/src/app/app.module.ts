import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {HttpModule} from '@angular/http';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {UserModule} from './user/user.module';
import { FacebookModule } from 'ngx-facebook';

import {
  HelpersService,
  EmitterService,
  SharedService,
  ApiService,
  LocalStorageService,
  SharedModule
} from './shared';
import {PlayerService} from "./player/player.service";

import {
  AuthGuard,
  AuthAllSuccessGuard
} from './common';

import {AppComponent} from './app.component';
import {components} from './components/index';
import {routing} from './app-routing.module';
import {PlaylistModule} from "./playlist/playlist.module";


@NgModule({
  declarations: [
    AppComponent,
    ...components
  ],
  imports: [
    SharedModule,
    BrowserModule,
    HttpModule,
    UserModule,
    routing,
    BrowserAnimationsModule,
    FacebookModule.forRoot(),
    PlaylistModule
  ],
  providers: [
    HelpersService,
    AuthGuard,
    AuthAllSuccessGuard,
    EmitterService,
    SharedService,
    ApiService,
    LocalStorageService,
    PlayerService
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
}
