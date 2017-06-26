import { NgModule }                              from '@angular/core';
import { ShareButtonsModule }                    from "ng2-sharebuttons";
import { AuthModule }                            from "../auth/auth.module";
import { SharedModule }                          from "../shared/shared.module";
import { HomeService }                           from "./home.service";
import { HomeRoutingModule }                     from "./home-routing.module";
import { homeComponents, exportsHomeComponents } from "./index";

@NgModule({
    imports: [
        SharedModule,
        AuthModule,
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
