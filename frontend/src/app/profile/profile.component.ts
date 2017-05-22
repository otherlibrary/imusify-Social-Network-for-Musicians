import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {ProfileService} from "./profile.service";
import {IProfile} from "../interfases/profile/IProfile";

@Component({
  selector: 'app-profile',
  templateUrl: 'profile.component.html',
  styleUrls: ['profile.component.scss']
})
export class ProfileComponent implements OnInit {
  public username: string;
  private sub: any;

  constructor(
    private _route: ActivatedRoute,
    private _profileService: ProfileService
  ) {
  }

  ngOnInit() {
    // this._route.data.subscribe(
    //   (data: { profileData: IProfile }) => {
    //     console.log(data);
    //   }
    // );
    this.sub = this._route.params.subscribe(params => {
      this.username = params['id'];
      this._profileService.getProfileData(this.username).subscribe(data => console.log(data))
    });
  }

  ngOnDestroy() {
    this.sub.unsubscribe();
  }

}
