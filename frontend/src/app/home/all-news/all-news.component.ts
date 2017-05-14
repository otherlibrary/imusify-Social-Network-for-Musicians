import {Component, OnInit} from '@angular/core';
import {HomeService} from "../home.service";
import {EmitterService} from "../../shared/services/emitter.service";
import {IRecord, ITracksData} from "../../interfases";
import * as _ from 'lodash';
import {PlayerService} from "../../player/player.service";

@Component({
  selector: 'app-all-news',
  templateUrl: './all-news.component.html',
  styleUrls: ['./all-news.component.css']
})
export class AllNewsComponent implements OnInit {
  homeData: ITracksData;
  records: IRecord[];
  sharedUrl: null;
  private isPlayPlaylist: boolean = false;

  constructor(
    private _homeService: HomeService,
    private _playerService: PlayerService
  ) {}

  ngOnInit() {
    this.getAllNews();
  }

  getAllNews() {
    EmitterService.get('TOGGLE_PRELOADER').emit(true);
    this._homeService.getAllNews().subscribe(data => {
      this.homeData = data;
      this.records = _.sortBy(data.records, 'id');

      EmitterService.get('TOGGLE_PRELOADER').emit(false);
    });
  }
  playPlaylist(record: IRecord) {
    this._playerService.playInputSubject.next(record);
    if(!this.isPlayPlaylist) {
      console.log('set playlist');
      //this._sharedService.setPlaylistSubject.next(this.records);
      this.isPlayPlaylist = true;
    }
  }
}
