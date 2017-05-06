import { NgModule } from '@angular/core';
import { UploadRoutingModule } from "./upload-routing.module";
import { NgUploaderModule } from 'ngx-uploader';
import {SharedModule} from "../shared/shared.module";

import { UploadService } from "./upload.service";

import { uploadComponents } from './index';

@NgModule({
    imports: [
        SharedModule,
        UploadRoutingModule,
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
