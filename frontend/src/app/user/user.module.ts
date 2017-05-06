import {NgModule} from '@angular/core';
import {SelectModule} from 'ng-select';
import {MyDatePickerModule} from 'mydatepicker';

import {userComponents, exportsUserComponents} from './index';

import {SharedModule} from "../shared/shared.module";
import {AuthService} from "../shared/services/auth.service";

@NgModule({
    imports: [
        SelectModule,
        MyDatePickerModule,
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
