import {NgModule}     from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {HomeComponent} from "./home.component";
import {AllNewsComponent} from "./all-news/all-news.component";
import {MusicComponent} from "./music/music.component";
import {FollowingComponent} from "./following/following.component";
import {StaticComponent} from "./static/static.component";
import {StaticPrivacyComponent} from "./static/static-privacy/static-privacy.component";
import {StaticLegalComponent} from "./static/static-legal/static-legal.component";
import {StaticVideoComponent} from "./static/static-video/static-video.component";
import {StaticTermsComponent} from "./static/static-terms/static-terms.component";
import {ArticlesComponent} from "./articles/articles.component";
import {BeatsComponent} from "./beats/beats.component";
import {LicenceComponent} from "./licence/licence.component";
import {HomeResolverService} from "./home-resolver.service";


const routes: Routes = [
  {
    path: '',
    component: HomeComponent,
    resolve: {
      homeData: HomeResolverService
    },
    children: [
      {
        path: '',
        component: AllNewsComponent,
      },
      {
        path: 'following',
        component: FollowingComponent
      },
      {
        path: 'music',
        component: MusicComponent
      },
      {
        path: 'static',
        component: StaticComponent,
        children: [
          {
            path: "",
            redirectTo: "privacy",
            pathMatch: "full"
          },
          {
            path: 'privacy',
            component: StaticPrivacyComponent,
          },
          {
            path: 'legal',
            component: StaticLegalComponent,
          },
          {
            path: 'video-privacy',
            component: StaticVideoComponent,
          },
          {
            path: 'terms',
            component: StaticTermsComponent
          }
        ]
      },
      {
        path: 'articles',
        component: ArticlesComponent
      },
      {
        path: 'beats',
        component: BeatsComponent
      },
      {
        path: 'licence',
        component: LicenceComponent
      }
    ]
  }
];

@NgModule({
  exports: [RouterModule],
  imports: [RouterModule.forChild(routes)],
  providers: [HomeResolverService]
})
export class HomeRoutingModule {
}
