import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {SelectModule} from 'angular2-select';
import {MyDatePickerModule} from 'mydatepicker';

import {userComponents, exportsUserComponents} from './index';

import {SharedModule} from "../shared/shared.module";
import {AuthService} from "../shared/services/auth.service";

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        ReactiveFormsModule,
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
