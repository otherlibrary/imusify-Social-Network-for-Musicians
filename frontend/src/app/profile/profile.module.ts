import {NgModule} from '@angular/core';
import {ProfileRoutingModule} from "./profile-routing.module";
import {SharedModule} from "../shared/shared.module";
import {profileComponents} from "./index";

@NgModule({
    imports: [
        SharedModule,
        ProfileRoutingModule
    ],
    declarations: [
        ...profileComponents
    ]
})
export class ProfileModule {
}
