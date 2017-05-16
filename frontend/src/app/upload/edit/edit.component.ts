import {Component, Input, OnInit} from '@angular/core';
import {UploadService} from '../upload.service';
import 'rxjs/add/operator/switchMap';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Genre, IMood} from "../../interfases";
import {IMyOptions} from "mydatepicker";
import {UploadFileData} from "app/interfases/upload/IUploadFileData";

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
  public uploadTrackInfo: UploadFileData;
  @Input() genresList: Genre[];
  @Input() secGenresList: Genre[];
  @Input() trackTypesList: any[];
  @Input() moodsList: IMood[];

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
   * building form
   */
  buildForm() {
    this.uploadTrackInfo = this._uploadService.uploadTrackInfo;

    this.uploadTrackForm = this.fb.group({
      filename: this.uploadTrackInfo.file_name,
      track_id: this.uploadTrackInfo.track_id,
      waveform: this.uploadTrackInfo.waveform,
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
      second_genre_id: this.uploadTrackInfo.genre_id,
      pick_moods: this.uploadTrackInfo.pick_moods_id,
      type_artist: this.uploadTrackInfo.type_artist,
      is_public: this.uploadTrackInfo.is_public || "1",

      album: this.uploadTrackInfo.album,
      single: this.uploadTrackInfo.single,
      advertising: this.uploadTrackInfo.advertising,
      corporate: this.uploadTrackInfo.corporate,
      documentaryFilm: this.uploadTrackInfo.documentaryFilm,
      film: this.uploadTrackInfo.film,
      software: this.uploadTrackInfo.software,
      internetVideo: this.uploadTrackInfo.internetVideo,
      liveEvent: this.uploadTrackInfo.liveEvent,
      musicHold: this.uploadTrackInfo.musicHold,
      musicProd1k: this.uploadTrackInfo.musicProd1k,
      musicProd10k: this.uploadTrackInfo.musicProd10k,
      musicProd50k: this.uploadTrackInfo.musicProd50k,
      musicProd51k: this.uploadTrackInfo.musicProd51k,
      website: this.uploadTrackInfo.website,
      advertisingE: this.uploadTrackInfo.advertisingE,
      corporateE: this.uploadTrackInfo.corporateE,
      documentaryFilmE: this.uploadTrackInfo.documentaryFilmE,
      filmE: this.uploadTrackInfo.filmE,
      softwareE: this.uploadTrackInfo.softwareE,
      internetVideoE: this.uploadTrackInfo.internetVideoE,
      liveEventE: this.uploadTrackInfo.liveEventE,
      musicHoldE: this.uploadTrackInfo.musicHoldE,
      musicProd1kE: this.uploadTrackInfo.musicProd1kE,
      musicProd10kE: this.uploadTrackInfo.musicProd10kE,
      musicProd50kE: this.uploadTrackInfo.musicProd50kE,
      musicProd51kE: this.uploadTrackInfo.musicProd51kE,
      websiteE: this.uploadTrackInfo.websiteE,
      nonProfit: this.uploadTrackInfo.nonProfit,
      neverSale: this.uploadTrackInfo.neverSale
    });

    this.uploadTrackForm.valueChanges
      .subscribe(data => {
        this.onValueChange(data);
        console.log(data);
      });
    this.onValueChange();
  }

  constructor(
    private _uploadService: UploadService,
    private fb: FormBuilder
  ) {}

  ngOnInit() {
    this.buildForm();
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
  public checkImage(event) {
    console.log(event);
  }

  /**
   * submit form
   * @param e
   */
  public onSubmit(e) {
    console.log(e);
  }

  public test() {
    console.log(this._uploadService.uploadTrackInfo);
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

  public saleIsOpen: boolean = true;
  public switchSale() {
    this.saleIsOpen = !this.saleIsOpen;
  }

  public licensingIsOpen: boolean = true;
  public switchLicensing() {
    this.licensingIsOpen = !this.licensingIsOpen;
  }

  public licensingEIsOpen: boolean = true;
  public switchELicensing() {
    this.licensingEIsOpen = !this.licensingEIsOpen;
  }

  public noneProfitIsOpen: boolean = true;
  public switchNoneProfit() {
    this.noneProfitIsOpen = !this.noneProfitIsOpen;
  }

  public switchNeverSale() {
    this.saleIsOpen = false;
    this.licensingIsOpen = false;
    this.licensingEIsOpen = false;
    this.noneProfitIsOpen = false;
  }

  public priceStatusCheck: boolean = false;
  public priceReady: boolean = false;
  public toggleCheck() {
    this.priceStatusCheck = !this.priceStatusCheck;
    this.priceReady = false;
  }
  public togglePrice() {
    if(this.priceStatusCheck) {
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
