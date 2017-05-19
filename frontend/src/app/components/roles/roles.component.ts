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

  saveUserRoles() {
    let strUserRoles = this._helperService.toStringParam({'user_roles[]': this.userRoles});
    console.log(strUserRoles);
  }

  testRole() {
    console.log(this.userRoles);
  }
}
