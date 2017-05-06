import {NgModule} from '@angular/core';
import {UserModule} from "../user/user.module";
import {SharedModule} from "../shared/shared.module";
import {HomeService} from "./home.service";
import {ShareButtonsModule} from "ng2-sharebuttons";
import {HomeRoutingModule} from "./home-routing.module";
import {homeComponents, exportsHomeComponents} from "./index";

@NgModule({
    imports: [
        SharedModule,
        UserModule,
        ShareButtonsModule,
        HomeRoutingModule
    ],
    declarations: [
        ...homeComponents
    ],
    exports: [
        ...exportsHomeComponents
    ],
    providers: [HomeService]
})
export class HomeModule {
}
