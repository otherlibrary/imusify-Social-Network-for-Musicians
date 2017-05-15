import {Component, OnInit} from '@angular/core';
import {HomeService} from "../home.service";
import {EmitterService} from "../../shared/services/emitter.service";
import {SharedService} from "../../shared/shared.service";
import {IRecord, ITracksData} from "../../interfases";
import {Observable} from "rxjs/Observable";

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

  constructor(private _homeService: HomeService, private _sharedService: SharedService) {
  }

  ngOnInit() {
    console.log('musik init');
    this.getMusic()
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

  /**
   * get music data
   */
  getMusic(): void {
    EmitterService.get('TOGGLE_PRELOADER').emit(true);
    this._homeService.getMusic().subscribe((data: ITracksData) => {
      this.musicData = data;
      this.records = this.musicData.records;

      EmitterService.get('GET_PROFILE').emit(data);
      EmitterService.get('TOGGLE_PRELOADER').emit(false);
    });
  }
}
