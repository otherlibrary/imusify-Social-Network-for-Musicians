import {NgModule} from '@angular/core';
import {ProfileRoutingModule} from "./profile-routing.module";
import {SharedModule} from "../shared/shared.module";
import {profileComponents} from "./index";
import {ProfileService} from "./profile.service";
import { ProfileUserComponent } from './profile-user/profile-user.component';

@NgModule({
  imports: [
    SharedModule,
    ProfileRoutingModule
  ],
  declarations: [
    ...profileComponents,
    ProfileUserComponent
  ],
  providers: [
    ProfileService
  ]
})
export class ProfileModule {
}
