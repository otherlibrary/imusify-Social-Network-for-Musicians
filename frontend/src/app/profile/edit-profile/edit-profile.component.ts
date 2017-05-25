import {Component, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute, NavigationEnd, Router} from "@angular/router";
import {FormGroup, FormBuilder, Validators} from "@angular/forms";
import {IProfileEdit} from "../../interfases/profile/IProfileEdit";
import {IMyOptions} from "mydatepicker";

import {IOption} from "ng-select";
import {SharedService} from "app/shared";
import {ProfileService} from "../profile.service";

@Component({
  selector: 'app-edit-profile',
  templateUrl: './edit-profile.component.html',
  styleUrls: ['./edit-profile.component.scss']
})
export class EditProfileComponent implements OnInit, OnDestroy {
  public editProfileForm: FormGroup;
  public profileData: IProfileEdit;
  public userId: string;
  public myDatePickerOptions: IMyOptions = {
    dateFormat: 'dd.mm.yyyy'
  };
  public currentDate: Object = null;

  public formErrors = {
    "firstname": "",
    "lastname": ""
  };
  public validationMessages = {
    "firstname": {
      "required": "Required field."
    },
    "lastname": {
      "required": "Required field."
    }
  };

  public countryList: IOption[];
  public stateList: IOption[];
  public cityList: IOption[];
  private sub: any;
  public submitted: boolean = false;

  constructor(private _router: Router,
              private fb: FormBuilder,
              private _sharedService: SharedService,
              private _profileService: ProfileService,
              private _route: ActivatedRoute) {
  }

  ngOnInit() {
    this.sub = this._route.parent.params.subscribe(params => {
      this.userId = params.id;
      this.getEditProfile(this.userId);
      this.getCountryList();
    });
  }

  ngOnDestroy() {
    this.sub.unsubscribe();
  }

  /**
   * build form edit Profile
   */
  public buildForm() {
    this.editProfileForm = this.fb.group({
      user_id: [this.userId],
      firstname: [this.profileData.firstname, [
        Validators.required
      ]],
      lastname: [this.profileData.lastname, [
        Validators.required
      ]],
      weburl: [this.profileData.weburl],
      countryId: [this.profileData.countryId],
      stateId: [this.profileData.stateId],
      cityId: [this.profileData.cityId],
      description: [this.profileData.description],
      birthdate: [this.profileData.birthdate],
      image: ['']
    });

    this.editProfileForm.valueChanges
      .subscribe(data => {
        this.onValueChange(data);
      });
    this.onValueChange();
  }

  /**
   * get all info of profile
   * @param userId
   */
  public getEditProfile(userId) {
    this._profileService.getEditProfile(userId).subscribe((data: IProfileEdit) => {
      this.profileData = data;
      //set date default
      let arrBirthdate = this.profileData.birthdate.split('.');
      this.currentDate = {
        date: {
          year: arrBirthdate[2],
          month: arrBirthdate[1],
          day: arrBirthdate[0]
        }
      };
      this.buildForm();
    })
  }

  /**
   * change profile image
   * @param event
   */
  changeImage(event) {
    let reader = new FileReader();
    reader.onload = (e: any) => {
      this.profileData.image = e.target.result;
      this.editProfileForm.patchValue({
        image: e.target.result
      });
    };
    reader.readAsDataURL(event.target.files[0]);
  }

  /**
   * callback on change form inputs
   * @param data
   */
  public onValueChange(data?: any) {
    if (!this.editProfileForm) return;
    let form = this.editProfileForm;

    for (let field in this.formErrors) {
      this.formErrors[field] = "";
      // form.get - получение элемента управления
      let control = form.get(field);

      if (control && control.dirty && !control.valid) {
        let message = this.validationMessages[field];
        for (let key in control.errors) {
          this.formErrors[field] += message[key] + " ";
        }
      }
    }
  }

  /**
   * submit edit profile form
   * @param event
   */
  public onSubmit(event) {
    this.submitted = true;
    event.preventDefault();
    this._profileService.updateProfileInfo(this.editProfileForm.value).subscribe(res => {
      //update local storage user data
      let userData = JSON.parse(localStorage.getItem('auth_data'));
      for(let key in res) {
        if(res.hasOwnProperty(key)) {
          userData[key] = res[key];
        }
      }
      this._sharedService.loginSubject.next(userData);
      this._sharedService.notificationSubject.next({
        title: 'Save Profile',
        msg: 'Success save',
        type: 'success'
      });
      this._router.navigate(['profile/' + this.userId]);
    }, err => {
      this._sharedService.notificationSubject.next({
        title: 'Save Profile',
        msg: 'Error save',
        type: 'error'
      });
    });
  };

  /**
   * get list of country
   */
  public getCountryList() {
    this._profileService.getCountryList().subscribe((country: IOption[]) => {
      this.countryList = country;
    });
  }

  /**
   * select country id
   * @param event
   */
  public selectCountry(event) {
    this._profileService.getStateList(event.value).subscribe((stateList: IOption[]) => {
      this.stateList = stateList;
    });
  }

  /**
   * select state id
   * @param event
   */
  public selectState(event) {
    this._profileService.getCityList(event.value).subscribe((cityList: IOption[]) => {
      this.cityList = cityList;
    });
  }

  /**
   * close popup edit profile
   */
  closePopup() {
    this._router.navigate(['profile/' + this.userId]);
  }

  test() {
    console.log(this.editProfileForm.value);
  }
}
