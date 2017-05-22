import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {ITracksData} from "../../interfases/ITracksData";
import {IRecord} from "../../interfases/IRecord";
import {SharedService} from "../../shared/shared.service";

@Component({
  selector: 'app-beats',
  templateUrl: './beats.component.html',
  styleUrls: ['./beats.component.scss']
})
export class BeatsComponent implements OnInit {
  public musicData: ITracksData;
  public records: IRecord[];
  private isPlayPlaylist: boolean = false;

  constructor(
    private _route: ActivatedRoute,
    private _sharedService: SharedService
  ) {}

  ngOnInit() {
    this._route.parent.data.subscribe(
      (data: { homeData: ITracksData }) => {
        this.musicData = data.homeData;
        this.records = this.musicData.records.filter((record: any) => {
          return record.trackuploadType == '2';
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
