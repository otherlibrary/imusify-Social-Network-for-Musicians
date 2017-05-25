import {NgModule} from '@angular/core';
import {ProfileRoutingModule} from "./profile-routing.module";
import {SharedModule} from "../shared/shared.module";
import {profileComponents} from "./index";
import {ProfileService} from "./profile.service";

@NgModule({
  imports: [
    SharedModule,
    ProfileRoutingModule
  ],
  declarations: [
    ...profileComponents
  ],
  providers: [
    ProfileService
  ]
})
export class ProfileModule {
}
