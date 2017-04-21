import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ProfileComponent} from "./profile.component";
import {OnlyMeComponent} from "./only-me/only-me.component";
import {PopularComponent} from "./popupar/popular.component";

const routes: Routes = [
  {
    path: "",
    component: ProfileComponent,
    children: [
      {
        path: "",
        redirectTo: "only-me",
        pathMatch: "full"
      },
      {
        path: "only-me",
        component: OnlyMeComponent
      },
      {
        path: "popular",
        component: PopularComponent
      }
    ]
  }
];

@NgModule({
  exports: [RouterModule],
  imports: [RouterModule.forChild(routes)]
})
export class ProfileRoutingModule {
}
