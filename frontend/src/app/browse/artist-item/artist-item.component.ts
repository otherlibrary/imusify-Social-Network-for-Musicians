import {Component, Input, OnInit} from '@angular/core';
import {IArtist} from "../../interfases/IArtist";
import {SharedService} from "../../shared/shared.service";

@Component({
  selector: 'artist-item',
  templateUrl: './artist-item.component.html',
  styleUrls: ['./artist-item.component.scss']
})
export class ArtistItemComponent implements OnInit {
  @Input() artist: IArtist;

  constructor(private _SharedService: SharedService) { }

  ngOnInit() {
  }

  playRecord(record): void {
    this._SharedService.playTrackSubject.next(record);
  }

}
