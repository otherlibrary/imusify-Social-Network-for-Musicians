import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UploadRoutingModule } from "./upload-routing.module";
import { uploadComponents } from './index';
import { UploadService } from "./upload.service";

@NgModule({
    imports: [
        CommonModule,
        UploadRoutingModule
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
