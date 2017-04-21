import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";
import {AuthService} from "../shared/services/auth.service";
import {EmitterService} from "../shared/services/emitter.service";
import {HomeService} from "./home.service";

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {


  constructor(private _router: Router, private _userService: AuthService, private _homeService: HomeService) {

  }

  t() {
    console.log(this._userService.profileData);
    console.log(this._userService.loggedin);
  }

  ngOnInit() {
    console.log('init home component');
  }

  /**
   * open popup news
   * @returns {boolean}
   */
  goToNews() {
    this._router.navigate([{outlets: {popup: 'news'}}]);
    return false;
  }

  /**
   * open popup Search
   * @returns {boolean}
   */
  goToSearch() {
    this._router.navigate([{outlets: {popup: 'search'}}]);
    return false;
  }
}
