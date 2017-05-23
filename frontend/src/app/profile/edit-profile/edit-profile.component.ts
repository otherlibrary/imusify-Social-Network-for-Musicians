import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";
import {FormGroup, FormBuilder, Validators} from "@angular/forms";
import {IProfileEdit} from "../../interfases/profile/IProfileEdit";
import {IMyOptions} from "mydatepicker";
import {ProfileService} from "../profile.service";

@Component({
  selector: 'app-edit-profile',
  templateUrl: './edit-profile.component.html',
  styleUrls: ['./edit-profile.component.scss']
})
export class EditProfileComponent implements OnInit {
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

  public counryList: string[];

  constructor(
    private _router: Router,
    private fb: FormBuilder,
    private _profileService: ProfileService
  ) {}

  ngOnInit() {
    this.getCountryList();
    this.buildForm();
  }

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

  getCountryList() {
    this._profileService.getCountryList().subscribe(country => {
      console.log(country);
    });
  }

  closePopup() {
    this._router.navigate([{outlets: {popup: null}}]);
  }
}
