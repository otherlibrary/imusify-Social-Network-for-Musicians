import {Component, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {ProfileService} from "./profile.service";
import {IProfile} from "../interfases/profile/IProfile";
import {EmitterService} from "../shared/services/emitter.service";

@Component({
  selector: 'app-profile',
  templateUrl: 'profile.component.html',
  styleUrls: ['profile.component.scss']
})
export class ProfileComponent implements OnInit, OnDestroy {
  public profile: IProfile;

  constructor(
    private _route: ActivatedRoute
  ) {
  }

  ngOnInit() {
    this._route.data.subscribe(
      (data: { profileData: IProfile }) => {
        this.profile = data.profileData;
        this.profile['playlists'] = [
          {title: 'Untitled Groove', image: '/assets/images/feed-boxes/2.jpg', followers: "10", likes: "100", records: "8"},
          {title: 'Untitled Groove2', image: '/assets/images/feed-boxes/3.jpg', followers: "20", likes: "32", records: "57"},
          {title: 'Untitled Groove3', image: '/assets/images/feed-boxes/4.jpg', followers: "50", likes: "121", records: "33"},
          {title: 'Untitled Groove4', image: '/assets/images/feed-boxes/featured-track-bg.jpg', followers: "434", likes: "323", records: "545"},
          {title: 'Untitled Groove5', image: '/assets/images/profile/vinil-face1.jpg', followers: "10", likes: "121", records: "324"},
          ];
        EmitterService.get('TOGGLE_PRELOADER').emit(false);
      }
    );
  }

  ngOnDestroy() {
  }

}
