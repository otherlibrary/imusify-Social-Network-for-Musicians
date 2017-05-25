import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ProfileComponent} from "./profile.component";
import {ProfileResolverService} from "./profile-resolver.service";
import {EditProfileComponent} from "./edit-profile/edit-profile.component";

const routes: Routes = [
  {
    path: ":id",
    component: ProfileComponent,
    resolve: {
      profileData: ProfileResolverService
    },
    children: [
      {
        path: "edit",
        component: EditProfileComponent,
        outlet: 'profile'
      }
    ]
  }
];

@NgModule({
  exports: [RouterModule],
  imports: [RouterModule.forChild(routes)],
  providers: [ProfileResolverService]
})
export class ProfileRoutingModule {
}
