import {Component, Input, OnInit} from '@angular/core';
import {IProfile} from "../../interfases/profile/IProfile";


@Component({
  selector: 'app-profile-user',
  templateUrl: './profile-user.component.html',
  styleUrls: ['./profile-user.component.scss']
})
export class ProfileUserComponent implements OnInit {
  @Input() profile: IProfile;

  constructor(private swiper: Swiper) {
  }

  ngOnInit() {
  }

}
