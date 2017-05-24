import {Component, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {ProfileService} from "./profile.service";
import {IProfile} from "../interfases/profile/IProfile";

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
      }
    );
  }

  ngOnDestroy() {
  }

}
