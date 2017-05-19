import {Routes, RouterModule} from "@angular/router";
import {AuthGuard} from "./common/index";

import {AboutComponent} from "./components/about/about.component";
import {NotFoundComponent} from "./components/not-found/not-found.component";
import {SearchComponent} from "./components/search/search.component";
import {PlaylistComponent} from "./components/playlist/playlist.component";
import {FavoritesComponent} from "./components/favorites/favorites.component";
import {LoginComponent} from "./user/login/login.component";
import {SignupComponent} from "./user/signup/signup.component";
import {EmailComponent} from "./user/signup/email/email.component";
import {CheckoutComponent} from "./components/checkout/checkout.component";
import {SoungDetailComponent} from "./components/soung-detail/soung-detail.component";
import {AccountComponent} from "./components/account/account.component";
import {ChangePassComponent} from "./components/change-pass/change-pass.component";
import {StripeComponent} from "./components/stripe/stripe.component";
import {EditProfileComponent} from "./components/edit-profile/edit-profile.component";
import {RolesComponent} from "./components/roles/roles.component";
import {MembershipComponent} from "./components/membership/membership.component";
import {InviteFriendsComponent} from "./components/invite-friends/invite-friends.component";
import {NotificationsComponent} from "./components/notifications/notifications.component";
import {MessagesComponent} from "./components/messages/messages.component";
import {ArtistProfileComponent} from "./components/artist-profile/artist-profile.component";
import {NewsComponent} from "./components/news/news.component";
import {AuthAllSuccessGuard} from "./common/auth-all-success.guard";

const APP_ROUTES: Routes = [
  {
    path: "",
    redirectTo: "home",
    pathMatch: "full",
    canActivateChild: [AuthAllSuccessGuard]
  },
  {
    path: 'home',
    loadChildren: 'app/home/home.module#HomeModule',
    canActivateChild: [AuthAllSuccessGuard]
  },
  {
    path: 'browse',
    loadChildren: 'app/browse/browse.module#BrowseModule',
    canActivateChild: [AuthAllSuccessGuard]
  },
  {
    path: 'profile/:id',
    loadChildren: 'app/profile/profile.module#ProfileModule',
    canActivate: [AuthGuard]
  },
  {
    path: 'upload',
    loadChildren: 'app/upload/upload.module#UploadModule',
    canActivate: [AuthGuard]
  },
  {
    path: 'soung-detail',
    component: SoungDetailComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'account',
    component: AccountComponent,
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        component: ChangePassComponent,
      },
      {
        path: 'stripe',
        component: StripeComponent,
      }
    ]
  },
  {
    path: 'checkout',
    component: CheckoutComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'artist-profile',
    component: ArtistProfileComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'playlist',
    component: PlaylistComponent,
    outlet: 'popup',
    canActivate: [AuthGuard]
  },
  {
    path: 'notifications',
    component: NotificationsComponent,
    outlet: 'popup',
    canActivate: [AuthGuard]
  },
  {
    path: 'messages',
    component: MessagesComponent,
    outlet: 'popup',
    canActivate: [AuthGuard]
  },
  {
    path: 'favorites',
    component: FavoritesComponent,
    outlet: 'popup',
    canActivate: [AuthGuard]
  },
  {
    path: 'edit-profile',
    component: EditProfileComponent,
    outlet: 'popup',
    canActivate: [AuthGuard]
  },
  {
    path: 'roles',
    component: RolesComponent,
    outlet: 'popup',
    canActivate: [AuthGuard]
  },
  {
    path: 'membership',
    component: MembershipComponent,
    outlet: 'popup',
    canActivate: [AuthGuard]
  },
  {
    path: 'invite',
    component: InviteFriendsComponent,
    outlet: 'popup',
    canActivate: [AuthGuard]
  },
  {
    path: 'about',
    component: AboutComponent,
    outlet: 'popup'
  },
  {
    path: 'login',
    component: LoginComponent,
    outlet: 'popup'
  },
  {
    path: 'signup',
    component: SignupComponent,
    outlet: 'popup'
  },
  {
    path: 'search',
    component: SearchComponent,
    outlet: 'popup'
  },
  {
    path: 'news',
    component: NewsComponent,
    outlet: 'popup',
  },
  {
    path: 'signup/email',
    component: EmailComponent,
    outlet: 'popup'
  },
  {
    path: '404',
    component: NotFoundComponent
  },
  {
    path: '**',
    redirectTo: '/404',
    pathMatch: "full"
  }
];

export const routing = RouterModule.forRoot(APP_ROUTES, {useHash: true});
