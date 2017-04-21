import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {browseComponents} from "./index";
import {BrowseRoutingModule} from "./browse-routing.module";

@NgModule({
    imports: [
        CommonModule,
        BrowseRoutingModule
    ],
    declarations: [
        ...browseComponents
    ]
})
export class BrowseModule {
}
