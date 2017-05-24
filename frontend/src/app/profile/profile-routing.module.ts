import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ProfileComponent} from "./profile.component";
import {OnlyMeComponent} from "./only-me/only-me.component";
import {PopularComponent} from "./popupar/popular.component";
import {ProfileResolverService} from "./profile-resolver.service";
import {EditProfileComponent} from "../components/edit-profile/edit-profile.component";
import {AuthGuard} from "../common/auth.guard";

const routes: Routes = [
  {
    path: "",
    component: ProfileComponent,
    resolve: {
      profileData: ProfileResolverService
    },
  }
];

@NgModule({
  exports: [RouterModule],
  imports: [RouterModule.forChild(routes)],
  providers: [ProfileResolverService]
})
export class ProfileRoutingModule {
}
