import {Component, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {FormGroup, FormBuilder, Validators} from "@angular/forms";
import {IProfileEdit} from "../../interfases/profile/IProfileEdit";
import {IMyOptions} from "mydatepicker";
import {ProfileService} from "../../profile/profile.service";
import {IOption} from "ng-select";

@Component({
  selector: 'app-edit-profile',
  templateUrl: './edit-profile.component.html',
  styleUrls: ['./edit-profile.component.scss']
})
export class EditProfileComponent implements OnInit, OnDestroy {
  public editProfileForm: FormGroup;
  public profileData: IProfileEdit;
  public myDatePickerOptions: IMyOptions = {
    dateFormat: 'dd.mm.yyyy'
  };
  public currentDate: string = null;
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

  constructor(
    private _router: Router,
    private fb: FormBuilder,
    private _profileService: ProfileService,
    private _route: ActivatedRoute,
  ) {}

  ngOnInit() {
    this.sub = this._route.params.subscribe(params => {
      this.getEditProfile(params.id);
    });

    this.getCountryList();
    this.buildForm();
  }

  ngOnDestroy() {
    this.sub.unsubscribe();
  }

  /**
   * build form edit Profile
   */
  buildForm() {
    // get init group
    this.profileData = {
      firstname: '',
      lastname: '',
      weburl: '',
      countryId: '',
      stateId: '',
      cityId: '',
      description: '',
      birthdate: '',
      image: ''
    };

    this.editProfileForm = this.fb.group({
      firstname: [this.profileData.firstname, [
        Validators.required
      ]],
      lastname: [this.profileData.lastname, [
        Validators.required
      ]],
      weburl: this.profileData.weburl,
      countryId: this.profileData.countryId,
      stateId: this.profileData.stateId,
      cityId: this.profileData.cityId,
      description: this.profileData.description,
      birthdate: this.profileData.birthdate,
      image: this.profileData.image
    });

    this.editProfileForm.valueChanges
      .subscribe(data => {
        this.onValueChange(data);
      });
    this.onValueChange();
  }

  public getEditProfile(userId) {
    this._profileService.getEditProfile(userId).subscribe((data: IProfileEdit) => {
      this.profileData = data;
    })
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
    console.log(event);
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
    this._router.navigate([{outlets: {popup: null}}]);
  }
}
