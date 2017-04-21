import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ProfileRoutingModule} from "./profile-routing.module";
import {profileComponents} from "./index";

@NgModule({
    imports: [
        CommonModule,
        ProfileRoutingModule
    ],
    declarations: [
        ...profileComponents
    ]
})
export class ProfileModule {
}
