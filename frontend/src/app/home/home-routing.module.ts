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
import {InstrumentalComponent} from "./instrumental/instrumental.component";
import {LicenceComponent} from "./licence/licence.component";
import {NewsComponent} from "../components/news/news.component";
import {AuthGuard} from "../common/auth.guard";


const routes: Routes = [
  {
    path: '',
    component: HomeComponent,
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
        path: 'music',
        component: MusicComponent
      },
      {
        path: 'articles',
        component: ArticlesComponent
      },
      {
        path: 'instrumental',
        component: InstrumentalComponent
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
  imports: [RouterModule.forChild(routes)]
})
export class HomeRoutingModule {
}
