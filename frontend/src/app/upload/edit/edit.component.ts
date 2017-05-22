import {Component, Input, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import 'rxjs/add/operator/switchMap';
import {IGenre, IMood, IUploadFileData} from "../../interfases";
import {IMyOptions} from "mydatepicker";

import {UploadService} from '../upload.service';
import {HelpersService} from "../../shared/services/helpers.service";
import {SharedService} from "../../shared/shared.service";

function base64ArrayBuffer(arrayBuffer) {
  let base64 = '';
  let encodings = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

  let bytes = new Uint8Array(arrayBuffer);
  let byteLength = bytes.byteLength;
  let byteRemainder = byteLength % 3;
  let mainLength = byteLength - byteRemainder;

  let a, b, c, d;
  let chunk;

  // Main loop deals with bytes in chunks of 3
  for (let i = 0; i < mainLength; i = i + 3) {
    // Combine the three bytes into a single integer
    chunk = (bytes[i] << 16) | (bytes[i + 1] << 8) | bytes[i + 2];

    // Use bitmasks to extract 6-bit segments from the triplet
    a = (chunk & 16515072) >> 18; // 16515072 = (2^6 - 1) << 18
    b = (chunk & 258048) >> 12; // 258048   = (2^6 - 1) << 12
    c = (chunk & 4032) >> 6;// 4032     = (2^6 - 1) << 6
    d = chunk & 63;// 63       = 2^6 - 1

    // Convert the raw binary segments to the appropriate ASCII encoding
    base64 += encodings[a] + encodings[b] + encodings[c] + encodings[d]
  }

  // Deal with the remaining bytes and padding
  if (byteRemainder == 1) {
    chunk = bytes[mainLength];
    a = (chunk & 252) >> 2; // 252 = (2^6 - 1) << 2
    // Set the 4 least significant bits to zero
    b = (chunk & 3) << 4; // 3   = 2^2 - 1
    base64 += encodings[a] + encodings[b] + '=='
  } else if (byteRemainder == 2) {
    chunk = (bytes[mainLength] << 8) | bytes[mainLength + 1];

    a = (chunk & 64512) >> 10;// 64512 = (2^6 - 1) << 10
    b = (chunk & 1008) >> 4; // 1008  = (2^6 - 1) << 4

    // Set the 2 least significant bits to zero
    c = (chunk & 15) << 2; // 15    = 2^4 - 1

    base64 += encodings[a] + encodings[b] + encodings[c] + '='
  }

  return base64
}

@Component({
  selector: 'track-edit',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.scss']
})

/**
 * initial data from this form is uploadService.uploadTrackInfo
 */
export class EditComponent implements OnInit {
  public uploadTrackForm: FormGroup;
  public uploadTrackInfo: IUploadFileData;
  public uploadTrackImg: any;
  public currentDate: Object;
  public isSubmit: boolean = false;
  public prefixLicId: string = 'lic_id_';

  @Input() genresList: IGenre[];
  @Input() secGenresList: IGenre[];
  @Input() trackTypesList: any[];
  @Input() moodsList: IMood[];
  @Input() licensesList: any[];
  @Input() typePrice: string;
  @Input() openSwitch: boolean;
  @Input() editImage: string;

  public myDatePickerOptions: IMyOptions = {
    dateFormat: 'dd.mm.yyyy'
  };

  public formErrors = {
    "title": "",
    "release_date": "",
    "track_type": "",
    "genre_id": ""
  };

  validationMessages = {
    "title": {
      "required": "Required field."
    },
    "release_date": {
      "required": "Required field."
    },
    "track_type": {
      "required": "Required field."
    },
    "genre_id": {
      "required": "Required field."
    }
  };

  /**
   * stringify image to base 64
   * @param imageData: <Object>{ track_id: string, file: string, type: string }
   * @returns {any}
   * @private
   */
  private _stringifyImage(imageData: any) {
    if (imageData.file) {
      return imageData.file.hasOwnProperty('format') ? `data:${imageData.file.format};base64,${base64ArrayBuffer(imageData.file.data)}` : null;
    } else {
      return null;
    }
  }

  /**
   * building form
   */
  buildForm() {
    this.uploadTrackInfo = this._uploadService.uploadTrackInfo;
    this.uploadTrackImg = this._stringifyImage(this._uploadService.trackImage);

    const initGroup = {
      filename: this.uploadTrackInfo.file_name,
      track_id: this.uploadTrackInfo.track_id,
      waveform: null,
      title: [this.uploadTrackInfo.title, [
        Validators.required
      ]],
      desc: this.uploadTrackInfo.desc,
      release_date: [this.uploadTrackInfo.release_date, [
        Validators.required
      ]],
      track_type: [this.uploadTrackInfo.track_upload_type, [
        Validators.required
      ]],
      genre_id: [this.uploadTrackInfo.genre_id, [
        Validators.required
      ]],
      copyright: [this.uploadTrackInfo.copyright, [
        Validators.required
      ]],
      second_genre_id: this.uploadTrackInfo.secondary_genre_id,
      pick_moods: this.uploadTrackInfo.pick_moods_id,
      type_artist: this.uploadTrackInfo.type_artist,
      is_public: this.uploadTrackInfo.is_public
    };
    //move licenses to formGroup
    this.licensesList.map(item => {
      initGroup[this.prefixLicId + item.id] = null;
    });

    this.uploadTrackForm = this.fb.group(initGroup);

    this.uploadTrackForm.valueChanges
      .subscribe(data => {
        this.onValueChange(data);
      });
    this.onValueChange();
  }

  constructor(private _uploadService: UploadService,
              private _helpersService: HelpersService,
              private _sharedService: SharedService,
              private fb: FormBuilder) {
  }

  ngOnInit() {

    //if date none, set current date
    let date = new Date();
    this.currentDate = this._uploadService.uploadTrackInfo.release_date || {
        date: {
          year: date.getFullYear(),
          month: date.getMonth() + 1,
          day: date.getDate()
        }
      };
    this.buildForm();

    if (this.openSwitch) {
      //clear all data track
      this._uploadService.clearUploadTrackInfo();
      this._uploadService.trackImage.file = null;
      //if open edit load image
      this.uploadTrackImg = this.editImage;
      //open all switch if click to edit track
      this.toggleAllSwitch();
    }
  }

  /**
   * callback on change form inputs
   * @param data
   */
  public onValueChange(data?: any) {
    if (!this.uploadTrackForm) return;
    let form = this.uploadTrackForm;

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
   * change image track
   * @param event
   */
  public changeImage(event) {
    if (event.target.files && event.target.files[0]) {
      let reader = new FileReader();
      reader.onload = (event: any) => {
        this.uploadTrackImg = event.target.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    }
  }

  /**
   * upload image
   * @param trackId
   */
  public submitImage(trackId) {
    this._uploadService.trackImage.track_id = trackId;
    let req = this._helpersService.toStringParam({
      track_id: trackId,
      file: this.uploadTrackImg,
      type: 'base64'
    });
    this._uploadService.uploadImageTrack(req).subscribe(res => {
      this._uploadService.editPopupSubject.next(false);

      this._sharedService.notificationSubject.next({
        title: 'Save image',
        msg: 'Success save',
        type: 'success'
      });
    }, err => {
      console.log(err);
      this._sharedService.notificationSubject.next({
        title: 'Save image',
        msg: 'Error save',
        type: 'error'
      });
    })
  }

  /**
   * submit form
   * @param event
   */
  public onSubmit(event) {
    event.preventDefault();
    this.isSubmit = true;
    this.uploadTrackForm.controls['waveform'].setValue(this._uploadService.uploadTrackInfo.waveform);
    //save track info
    let resultFormJSON = JSON.stringify(this.uploadTrackForm.value);
    let release_date = this.uploadTrackForm.value.release_date;
    let resultForm = JSON.parse(resultFormJSON);
    resultForm.release_date = JSON.stringify(release_date);
    let formData = this._helpersService.toStringParam((resultForm));

    this._uploadService.uploadTrackDetails(formData).subscribe(res => {
      if (res.hasOwnProperty('track_id')) {
        if (res.track_id != 0) {
          this._sharedService.notificationSubject.next({
            title: 'Save file',
            msg: 'Success save',
            type: 'success'
          });
          //upload image
          this.submitImage(res.track_id);
          this.isSubmit = false;
        } else {
          this._sharedService.notificationSubject.next({
            title: 'Save file',
            msg: 'Error save',
            type: 'error'
          });
        }
      } else {
        this._sharedService.notificationSubject.next({
          title: 'Save file',
          msg: 'Error save',
          type: 'error'
        });
      }
    });
  }

  public anSubmitEdit(event) {
    event.preventDefault();
    this.isSubmit = true;
    //save track info
    let resultFormJSON = JSON.stringify(this.uploadTrackForm.value);
    let release_date = this.uploadTrackForm.value.release_date;
    let resultForm = JSON.parse(resultFormJSON);
    resultForm.release_date = JSON.stringify(release_date);
    let formData = this._helpersService.toStringParam((resultForm));

    this._uploadService.saveEditTrack(formData).subscribe(res => {
      this.isSubmit = false;

      this._sharedService.notificationSubject.next({
        title: 'Save file',
        msg: 'Success save',
        type: 'success'
      });
      if(this.uploadTrackImg) {
        this.submitImage(res.track_id);
      }
      //close popup
      this._uploadService.editPopupSubject.next(false);
    }, err => {
      console.log(err);
    });
  }

  public test() {
    console.log(this.uploadTrackForm.value);
  }

  /**
   * template toggle's & switchers
   */
  public closePopup() {
    this._uploadService.editPopupSubject.next(false);
  }

  public currentTab: number = 1;

  public toggleTabs(tab) {
    this.currentTab = tab;
  }

  /**
   * update value licenses for type
   * @param type: string (s: sale, l: licenses, el: exclusive licenses, np: non profit)
   * @param val: boolean (true: price, false: null)
   * @private
   */
  private _licenseFilterUpdate(type: string, val?: boolean) {
    const _cacheObj: Object = {};
    this.licensesList.map(item => {
      if (item.lic_type == type) {
        _cacheObj['lic_id_' + item.id] = val ? item[this.typePrice] : null;
      }
    });
    this.uploadTrackForm.patchValue(_cacheObj);
  }

  /**
   * sale switch
   * @type {boolean}
   */
  public saleIsOpen: boolean = false;

  private _switchSaleValue(flag) {
    if (!flag) {
      this._licenseFilterUpdate('s', false)
    } else {
      this.neverSaleIsOpen = false;
      this._licenseFilterUpdate('s', true);
    }
  }

  public switchSale() {
    this.saleIsOpen = !this.saleIsOpen;
    this._switchSaleValue(this.saleIsOpen);
  }

  /**
   * licensing switch
   * @type {boolean}
   */
  public licensingIsOpen: boolean = false;

  private _switchLicensingValue(flag) {
    if (!flag) {
      this._licenseFilterUpdate('l', false);
    } else {
      this.neverSaleIsOpen = false;
      this._licenseFilterUpdate('l', true);
    }
  }

  public switchLicensing() {
    this.licensingIsOpen = !this.licensingIsOpen;
    this._switchLicensingValue(this.licensingIsOpen);
  }

  /**
   * licensing Exclusive switch
   * @type {boolean}
   */
  public licensingEIsOpen: boolean = false;

  private _switchLicensingEValue(flag) {
    if (!flag) {
      this._licenseFilterUpdate('el', false);
    } else {
      this.neverSaleIsOpen = false;
      this._licenseFilterUpdate('el', true);
    }
  }

  public switchELicensing() {
    this.licensingEIsOpen = !this.licensingEIsOpen;
    this._switchLicensingEValue(this.licensingEIsOpen);
  }

  /**
   * non profit switch
   * @type {boolean}
   */
  public noneProfitIsOpen: boolean = false;

  public switchNoneProfit() {
    this.noneProfitIsOpen = !this.noneProfitIsOpen;
    if (!this.noneProfitIsOpen) {
      this._licenseFilterUpdate('np', false);
    } else {
      this._licenseFilterUpdate('np', true);
    }
  }

  /**
   * all switchers open
   */
  public toggleAllSwitch() {
    this.switchSale();
    this.switchLicensing();
    this.switchELicensing();
    this.switchNoneProfit();
  }

  /**
   * never sale switch
   */
  public neverSaleIsOpen: boolean = false;

  public switchNeverSale() {
    this.neverSaleIsOpen = !this.neverSaleIsOpen;
    this.saleIsOpen = false;
    this.licensingIsOpen = false;
    this.licensingEIsOpen = false;
    this.noneProfitIsOpen = false;

    this._switchSaleValue(false);
    this._switchLicensingValue(false);
    this._switchLicensingEValue(false);
    this._licenseFilterUpdate('np', false);
  }

  /**
   * button-input price toggles
   * @type {boolean}
   */
  public priceStatusCheck: boolean = false;
  public priceReady: boolean = false;

  public toggleCheck() {
    this.priceStatusCheck = !this.priceStatusCheck;
    this.priceReady = false;
  }

  public togglePrice() {
    if (this.priceStatusCheck) {
      this.priceReady = !this.priceReady;
    }
  }

  /**
   * update form value on change price
   * @param event
   */
  public updatePrice(event) {
    this.uploadTrackForm.controls[event.id].setValue(event.price);
  }

  /**
   * if input checked set price, else set empty string
   * @param event
   */
  checkPrice(event) {
    event.status ? this.uploadTrackForm.controls[event.id].setValue(event.price) : this.uploadTrackForm.controls[event.id].setValue(null);
  }
}
