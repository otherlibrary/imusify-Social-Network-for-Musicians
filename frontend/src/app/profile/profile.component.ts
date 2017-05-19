import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {ProfileService} from "./profile.service";

@Component({
  selector: 'app-profile',
  templateUrl: 'profile.component.html',
  styleUrls: ['profile.component.scss']
})
export class ProfileComponent implements OnInit {
  public username: string;
  private sub: any;

  constructor(
    private route: ActivatedRoute,
    private _profileService: ProfileService
  ) {
  }

  ngOnInit() {
    this.sub = this.route.params.subscribe(params => {
      this.username = params['id'];
      //this._profileService.getUserData(this.username).subscribe(data => console.log(data))
    });
  }

  ngOnDestroy() {
    this.sub.unsubscribe();
  }

}
