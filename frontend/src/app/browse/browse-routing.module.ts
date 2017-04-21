import {NgModule}     from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {BrowseComponent} from "./browse.component";
import {BrowseExploreComponent} from "./browse-explore/browse-explore.component";
import {BrowseRecommendedComponent} from "./browse-recommended/browse-recommended.component";
import {PopularTracksComponent} from "./popular-tracks/popular-tracks.component";
import {NewTracksComponent} from "./new-tracks/new-tracks.component";
import {PopularArtistsComponent} from "./popular-artists/popular-artists.component";
import {NewArtistsComponent} from "./new-artists/new-artists.component";
import {PopularPlaylistsComponent} from "./popular-playlists/popular-playlists.component";
import {NewPlaylistsComponent} from "./new-playlists/new-playlists.component";


const routes: Routes = [
    {
        path: '',
        component: BrowseComponent,
        children: [
            {
                path: '',
                redirectTo: "recommended",
                pathMatch: "full"
            },
            {
                path: 'recommended',
                component: BrowseRecommendedComponent,
                children: [
                    {
                        path: '',
                        redirectTo: "popular-tracks",
                        pathMatch: "full"
                    },
                    {
                        path: 'popular-tracks',
                        component: PopularTracksComponent,
                    },
                    {
                        path: 'new-tracks',
                        component: NewTracksComponent,
                    },
                    {
                        path: 'popular-artists',
                        component: PopularArtistsComponent,
                    },
                    {
                        path: 'new-artists',
                        component: NewArtistsComponent,
                    },
                    {
                        path: 'popular-playlists',
                        component: PopularPlaylistsComponent,
                    },
                    {
                        path: 'new-playlists',
                        component: NewPlaylistsComponent,
                    }
                ]
            },
            {
                path: 'browse-explore',
                component: BrowseExploreComponent,
            }
        ]
    }
];

@NgModule({
    exports: [RouterModule],
    imports: [RouterModule.forChild(routes)]
})
export class BrowseRoutingModule {
}
