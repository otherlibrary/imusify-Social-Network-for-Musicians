import { NgModule } from '@angular/core';

import { userComponents, exportsUserComponents } from './index';

import { SharedModule } from "../shared/shared.module";
import { AuthService }  from "../shared/services/auth.service";

@NgModule({
    imports: [
        SharedModule
    ],
    declarations: [
        ...userComponents
    ],
    exports: [
        ...exportsUserComponents
    ],
    providers: [
        AuthService
    ]
})
export class UserModule {
}
