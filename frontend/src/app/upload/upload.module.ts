import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UploadRoutingModule } from "./upload-routing.module";
import { NgUploaderModule } from 'ngx-uploader';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {SharedModule} from "../shared/shared.module";

import { UploadService } from "./upload.service";

import { uploadComponents } from './index';

@NgModule({
    imports: [
        CommonModule,
        UploadRoutingModule,
        FormsModule,
        ReactiveFormsModule,
        NgUploaderModule,
        SharedModule
    ],
    declarations: [
        ...uploadComponents
    ],
    providers: [
        UploadService
    ]
})
export class UploadModule {
}
