import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {HttpModule} from '@angular/http';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {UserModule} from './user/user.module';

import {
  HelpersService,
  EmitterService,
  SharedService,
  ApiService,
  LocalStorageService,
  SharedModule
} from './shared';

import {
  AuthGuard,
  AuthAllSuccessGuard
} from './common';

import {AppComponent} from './app.component';
import {components} from './components/index';
import {routing} from './app-routing.module';
import {ProfileService} from "./profile/profile.service";

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
    BrowserAnimationsModule
  ],
  providers: [
    HelpersService,
    AuthGuard,
    AuthAllSuccessGuard,
    EmitterService,
    SharedService,
    ApiService,
    LocalStorageService,
    ProfileService
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
}
