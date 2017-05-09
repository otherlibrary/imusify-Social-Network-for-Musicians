import {Component, Input, OnInit} from '@angular/core';
import {UploadService} from '../upload.service';
import 'rxjs/add/operator/switchMap';
import {UploadFileData} from "../../interfases";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Genre} from "../../interfases/IGenre";
import {IMood} from "../../interfases/IMood";
import {IMyOptions} from "mydatepicker";

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

export class EditComponent implements OnInit {
  @Input() genresList: Genre[];
  @Input() secGenresList: Genre[];
  @Input() trackTypesList: any[];
  @Input() moodsList: IMood[];

  //TODO(AlexSol): change to 1
  public currentTab: number = 2;
  public saleStatus: boolean = false;
  public licensingStatus: boolean = false;
  public nonProfitStatus: boolean = false;

  public uploadTrackForm: FormGroup;
  public trackImage: any;
  public uploadTrackInfo: UploadFileData;
  public currentDate: Object;
  //TODO(AlexSol): type
  public sellData: any;
  public salePlaceholder: any;

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

  buildForm() {
    this.uploadTrackForm = this.fb.group({
      "title": [this.uploadTrackInfo.title, [
        Validators.required
      ]],
      "desc": this.uploadTrackInfo.desc,
      "release_date": [this.uploadTrackInfo.release_date, [
        Validators.required
      ]],
      "track_type": [this.uploadTrackInfo.track_upload_type, [
        Validators.required
      ]],
      "genre_id": [this.uploadTrackInfo.genre_id, [
        Validators.required
      ]],
      "second_genre_id": this.uploadTrackInfo.genre_id,
      "pick_moods": this.uploadTrackInfo.genre_id,
      "type_artist": this.uploadTrackInfo.type_artist,
      "is_public": this.uploadTrackInfo.is_public,
    });

    this.uploadTrackForm.valueChanges
      .subscribe(data => {
        console.log(data);
        this.onValueChange(data);
      });
    this.onValueChange();
  }

  constructor(private _uploadService: UploadService,
              private fb: FormBuilder) {
  }

  updatePrice(e) {
    this.sellData[e.id] = e.price;
  }

  checkedPrice(e) {
    if(!e.status) {
      this.sellData[e.id] = '';
    } else {
      this.sellData[e.id] = e.price
    }
  }

  ngOnInit() {
    this.uploadTrackInfo = this._uploadService.uploadTrackInfo;
    this.buildForm();

    //default data
    this.sellData = {
      album: '',
      single: '',
      advertising: '',
      corporate: '',
      documentaryFilm: '',
      film: '',
      software: '',
      internetVideo: '',
      liveEvent: '',
      musicHold: '',
      musicProd1k: '',
      musicProd10k: '',
      musicProd50k: '',
      musicProd51k: '',
      website: '',

      advertisingE: '',
      corporateE: '',
      documentaryFilmE: '',
      filmE: '',
      softwareE: '',
      internetVideoE: '',
      liveEventE: '',
      musicHoldE: '',
      musicProd1kE: '',
      musicProd10kE: '',
      musicProd50kE: '',
      musicProd51kE: '',
      websiteE: '',

      nonProfit: '',
      neverSale: false
    };

    this.salePlaceholder = {
      album: '9.99',
      single: '0.99',
      advertising: '225',
      corporate: '495',
      documentaryFilm: '60',
      film: '120',
      software: '300',
      internetVideo: '3',
      liveEvent: '15',
      musicHold: '30',
      musicProd1k: '35',
      musicProd10k: '105',
      musicProd50k: '175',
      musicProd51k: '245',
      website: '5',

      advertisingE: '2250',
      corporateE: '4950',
      documentaryFilmE: '600',
      filmE: '1200',
      softwareE: '3000',
      internetVideoE: '30',
      liveEventE: '150',
      musicHoldE: '300',
      musicProd1kE: '350',
      musicProd10kE: '1050',
      musicProd50kE: '1750',
      musicProd51kE: '2450',
      websiteE: '50',

      nonProfit: '0',
      neverSale: null
    };

    //Set default value in form
    let date = new Date();
    this.currentDate = {
      date: {
        year: date.getFullYear(),
        month: date.getMonth() + 1,
        day: date.getDate()
      }
    };
    this.uploadTrackForm.patchValue({
      is_public: "1"
    });

    //sound image
    if (this._uploadService.trackImage) {
      this.trackImage = `data:${this._uploadService.trackImage.format};base64,${base64ArrayBuffer(this._uploadService.trackImage.data)}`;
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
    this._uploadService.editPopupSubject.next(false);
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

  showTest() {
    console.log('sellData: ', this.sellData);
    console.log('uploadTrackInfo: ', this.uploadTrackInfo);
  }
}
