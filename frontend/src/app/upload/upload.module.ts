import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UploadRoutingModule } from "./upload-routing.module";
import { uploadComponents } from './index';
import { UploadService } from "./upload.service";
import { NgUploaderModule } from 'ngx-uploader';
import {FormsModule} from "@angular/forms";

@NgModule({
    imports: [
        CommonModule,
        UploadRoutingModule,
        FormsModule,
        NgUploaderModule
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
