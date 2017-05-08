import {Component, OnInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {UploadService} from '../upload.service';
import 'rxjs/add/operator/switchMap';
import {UploadFileData} from "../../interfases";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

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
  selector: 'app-edit',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.scss']
})

export class EditComponent implements OnInit {
  public currentTab: number = 1;
  public saleStatus: boolean = false;
  public licensingStatus: boolean = false;
  public nonProfitStatus: boolean = false;

  public uploadTrackForm: FormGroup;
  public trackImage: any;
  public uploadTrackInfo: UploadFileData;
  //TODO(AlexSol): type
  public sellData: any;

  public formErrors = {
    "title": "",
    "releaseDate": "",
    "trackType": "",
    "genreId": "",
    "secondGenreId": ""
  };

  validationMessages = {
    "title": {
      "required": "Required field."
    },
    "releaseDate": {
      "required": "Required field."
    },
    "trackType": {
      "required": "Required field."
    },
    "genreId": {
      "required": "Required field."
    }
  };

  buildForm() {
    this.uploadTrackForm = this.fb.group({
      "title": [this.uploadTrackInfo.title, [
        Validators.required
      ]],
      "releaseDate": [this.uploadTrackInfo.release_date, [
        Validators.required
      ]],
      "trackType": [this.uploadTrackInfo.track_upload_type, [
        Validators.required
      ]],
      "genreId": [this.uploadTrackInfo.genre_id, [
        Validators.required
      ]]
    });

    this.uploadTrackForm.valueChanges
      .subscribe(data => {
        console.log(data);
        this.onValueChange(data);
      });

    this.onValueChange();
  }

  constructor(private _router: Router,
              private _UploadService: UploadService,
              private fb: FormBuilder) {
  }

  updatePrice(e) {
    this.sellData[e.id] = e.price;
  }

  ngOnInit() {
    this.uploadTrackInfo = this._UploadService.uploadTrackInfo;
    //sound image
    if(this._UploadService.trackImage) {
      this.trackImage = `data:${this._UploadService.trackImage.format};base64,${base64ArrayBuffer(this._UploadService.trackImage.data)}`;
    }

    this.buildForm();

    this.sellData = {
      album: '9.99',
      single: '0.99'
    }
  }

  onValueChange(data?: any) {
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

  closePopup() {
    this._router.navigate(['upload']);
  }

  onSubmit() {
    console.log('submit');
  }

  toggleTabs(tab) {
    this.currentTab = tab;
  }

  toggleNonProfit() {
    this.nonProfitStatus = !this.nonProfitStatus;
    this.licensingStatus = false;
    this.saleStatus = false;
  }

  toggleLicensingStatus() {
    this.licensingStatus = !this.licensingStatus;
  }

  toggleSaleStatus() {
    this.saleStatus = !this.saleStatus;
  }
}
