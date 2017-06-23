import { NgModule }                from '@angular/core';
import { BrowserModule }           from '@angular/platform-browser';
import { HttpModule }              from '@angular/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { FacebookModule }          from 'ngx-facebook';

import { UserModule }              from './user/user.module';

import {
    HelpersService,
    EmitterService,
    SharedService,
    ApiService,
    LocalStorageService,
    SharedModule
} from './shared';
import { PlayerService } from "./player/player.service";

import {
    AuthGuard,
    AuthAllSuccessGuard
} from './common';

import { AppComponent } from './app.component';
import { AppConfig }    from './app.config';
import { components }   from './components/index';
import { routing }      from './app-routing.module';


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
        FacebookModule.forRoot()
    ],
    providers: [
        HelpersService,
        AuthGuard,
        AuthAllSuccessGuard,
        EmitterService,
        SharedService,
        ApiService,
        AppConfig,
        LocalStorageService,
        PlayerService
    ],
    bootstrap: [AppComponent]
})
export class AppModule {
}
