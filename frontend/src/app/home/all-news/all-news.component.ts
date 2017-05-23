import {Component, OnInit} from '@angular/core';
import {HomeService} from "../home.service";
import {EmitterService} from "../../shared/services/emitter.service";
import {IRecord, ITracksData} from "../../interfases";
import {SharedService} from "../../shared/shared.service";
import {HelpersService} from "../../shared/services/helpers.service";
import {IArticle} from "../../interfases/IArticle";
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-all-news',
  templateUrl: './all-news.component.html',
  styleUrls: ['./all-news.component.css']
})
export class AllNewsComponent implements OnInit {
  homeData: ITracksData;
  records: IRecord[] = [];
  sharedUrl: null;
  private isPlayPlaylist: boolean = false;

  constructor(
    private _sharedService: SharedService,
    private _helpersService: HelpersService,
    private _route: ActivatedRoute,
  ) {}

  ngOnInit() {
    // get cache all news
    this._route.data.subscribe(
      (data: { homeData: ITracksData }) => {
        this.homeData = data.homeData;
        let len = this.homeData.records.length;

        this.homeData.records.map((item: any, index) => {
          this.records.push(item);

          if(item.is_article) {
            let random = this._helpersService.getRandomInt(0, len);
            this._helpersService.move(this.records, index, random);
          }
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
}
