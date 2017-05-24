import {Component, Input, OnInit} from '@angular/core';
import {IProfile} from "../../interfases/profile/IProfile";

@Component({
  selector: 'app-profile-artist',
  templateUrl: './profile-artist.component.html',
  styleUrls: ['./profile-artist.component.scss']
})
export class ProfileArtistComponent implements OnInit {
  @Input() profile: IProfile;

  constructor() { }

  ngOnInit() {
  }

}
