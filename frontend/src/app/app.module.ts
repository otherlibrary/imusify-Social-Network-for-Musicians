import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {FormsModule} from '@angular/forms';
import {HttpModule} from '@angular/http';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';

import {HelpersService} from './shared/services/helpers.service';
import {EmitterService} from './shared/services/emitter.service';

import {AuthGuard} from './common/index';
import {UserModule} from './user/user.module';
import {SharedModule} from './shared/shared.module';

import {routing} from './app-routing.module';
import {AppComponent} from './app.component';
import {components} from './components/index';

@NgModule({
  declarations: [
    AppComponent,
    ...components
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    UserModule,
    SharedModule,
    routing,
    BrowserAnimationsModule
  ],
  providers: [HelpersService, AuthGuard, EmitterService],
  bootstrap: [AppComponent]
})
export class AppModule {
}
