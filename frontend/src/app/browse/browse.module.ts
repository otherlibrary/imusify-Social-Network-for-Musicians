import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {browseComponents} from "./index";
import {BrowseRoutingModule} from "./browse-routing.module";
import {BrowseService} from "./browse.service";

@NgModule({
    imports: [
        CommonModule,
        BrowseRoutingModule
    ],
    declarations: [
        ...browseComponents
    ],
    providers: [
        BrowseService
    ]
})
export class BrowseModule {
}
