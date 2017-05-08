import {NgModule}     from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {UploadComponent} from "./upload.component";
import {AudioComponent} from "./audio/audio.component";
import {AlbumsComponent} from "./albums/albums.component";
import {UploadPlaylistComponent} from "./upload-playlist/upload-playlist.component";
import {PicturesComponent} from "./pictures/pictures.component";


const routes: Routes = [
  {
    path: '',
    component: UploadComponent,
    children: [
      {
        path: '',
        component: AudioComponent,
      },
      {
        path: 'albums',
        component: AlbumsComponent,
      },
      {
        path: 'playlist',
        component: UploadPlaylistComponent,
      },
      {
        path: 'pictures',
        component: PicturesComponent
      }
    ]
  }
];

@NgModule({
  exports: [RouterModule],
  imports: [RouterModule.forChild(routes)]
})
export class UploadRoutingModule {
}
