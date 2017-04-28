import {Component, OnInit} from '@angular/core';
import {BrowseService} from "../browse.service";
import {IArtistData, IArtist} from "../../interfases";

@Component({
  selector: 'app-popular-artists',
  templateUrl: './popular-artists.component.html',
  styleUrls: ['./popular-artists.component.scss']
})
export class PopularArtistsComponent implements OnInit {
  public popularAttistsData: IArtistData;
  public artists: IArtist[];

  constructor(private _BrowseService: BrowseService) {
  }

  ngOnInit() {
    this.getPopularArtist();
  }

  getPopularArtist() {
    this._BrowseService.getPopularArtist().subscribe((data: IArtistData) => {
      this.popularAttistsData = data;
      this.artists = data.data_array;
      console.log(this.artists);
    })
  }

}
