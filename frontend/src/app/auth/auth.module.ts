import { NgModule } from '@angular/core';

import { authComponents, exportsAuthComponents } from './index';

import { SharedModule } from "../shared/shared.module";
import { AuthService }  from "../shared/services/auth.service";

@NgModule({
    imports: [
        SharedModule
    ],
    declarations: [
        ...authComponents
    ],
    exports: [
        ...exportsAuthComponents
    ],
    providers: [
        AuthService
    ]
})
export class AuthModule {
}
