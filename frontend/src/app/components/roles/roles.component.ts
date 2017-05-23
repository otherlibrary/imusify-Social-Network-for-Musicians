import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";
import {SharedService} from "../../shared/shared.service";
import {IRole} from "../../interfases/IRole";
import {HelpersService} from "../../shared/services/helpers.service";

@Component({
  selector: 'app-roles',
  templateUrl: './roles.component.html',
  styleUrls: ['./roles.component.scss']
})
export class RolesComponent implements OnInit {
  public roles: IRole[];
  public userRoles: string[] = [];

  constructor(
    private _router: Router,
    private _sharedService: SharedService,
    private _helperService: HelpersService
  ) {}

  ngOnInit() {
    this.getUserRoles();
  }

  closePopup() {
    this._router.navigate([{outlets: {popup: null}}]);
  }

  /**
   * get user roles
   */
  getUserRoles() {
    this._sharedService.getUserRoles().subscribe((data: any) => {
      this.roles = data.roles;
    })
  }

  toggleRole(role: IRole): void {
    role.selected = !role.selected;
    if(role.selected) {
      this.userRoles.push(role.id);
    } else {
      this.userRoles = this.userRoles.filter(id => id !== role.id);
    }
  }

  /**
   * save user roles
   */
  saveUserRoles() {
    let param = '';
    this.userRoles.map(item => {
      param += this._helperService.toStringParam({"user_roles[]": item}) + '&';
    });

    this._sharedService.setUserRoles(param).subscribe(res => {
      if(res.status === 'success') {
        this._sharedService.notificationSubject.next({
          title: 'Set Roles',
          msg: res.msg,
          type: 'success'
        });
      } else if(res.status === 'error') {
        this._sharedService.notificationSubject.next({
          title: 'Set Roles',
          msg: res.msg,
          type: 'error'
        });
      }
    }, err => {
      this._sharedService.notificationSubject.next({
        title: 'Set Roles',
        msg: err.message,
        type: 'error'
      });
    });
  }

  testRole() {
    console.log(this.userRoles);
  }
}
