import {NgModule} from '@angular/core';
import {browseComponents} from "./index";
import {BrowseRoutingModule} from "./browse-routing.module";
import {BrowseService} from "./browse.service";
import {SharedModule} from "../shared/shared.module";

@NgModule({
    imports: [
        SharedModule,
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
