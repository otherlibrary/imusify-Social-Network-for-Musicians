import {Component, OnInit} from '@angular/core';
import {HomeService} from "../home.service";
import {SharedService} from "../../shared/shared.service";
import {IRecord, ITracksData} from "../../interfases";
import {ActivatedRoute} from "@angular/router";
import {IArticle} from "../../interfases/IArticle";

@Component({
  selector: 'app-music',
  templateUrl: './music.component.html',
  styleUrls: ['./music.component.css']
})
export class MusicComponent implements OnInit {
  public sharedUrl: null;
  public isVisible: boolean;

  public musicData: ITracksData;
  public records: IRecord[];
  private isPlayPlaylist: boolean = false;

  constructor(
    private _sharedService: SharedService,
    private _route: ActivatedRoute
  ) {}

  ngOnInit() {
    this._route.parent.data.subscribe(
      (data: { homeData: ITracksData }) => {
        this.musicData = data.homeData;
        this.records = this.musicData.records.filter((record: any) => {
          return record.is_track;
        });
      }
    );
  }

  playPlaylist() {
    if(!this.isPlayPlaylist) {
      console.log('set playlist');
      this._sharedService.setPlaylistSubject.next(this.records);
      this.isPlayPlaylist = true;
    }
  }

  /**
   * add to follow records
   * @param data
   */
  addFollow(data) {
    // this._homeService.addFollow(data).subscribe(data => {
    //     console.log(data);
    // })
  }

  sharedRecord(link) {
    this.sharedUrl = link;
  }
}
