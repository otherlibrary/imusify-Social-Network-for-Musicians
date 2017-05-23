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
  private sub: any;

  constructor(
    private _route: ActivatedRoute,
    private _profileService: ProfileService
  ) {
  }

  ngOnInit() {
    this.sub = this._route.params.subscribe(params => {
      this._profileService.getProfileData(params['id']).subscribe((data: IProfile) => {
        this.profile  = data;
      })
    });
  }

  ngOnDestroy() {
    this.sub.unsubscribe();
  }

}
