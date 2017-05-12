import {Component, Input, OnInit} from '@angular/core';
import {UploadService} from '../upload.service';
import 'rxjs/add/operator/switchMap';
import {UploadFileData} from "../../interfases";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Genre, IMood} from "../../interfases";
import {IMyOptions} from "mydatepicker";
import {LocalStorageService} from "../../shared/services/local-storage.service";
import * as _ from 'lodash';
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

export class EditComponent implements OnInit {
  @Input() genresList: Genre[];
  @Input() secGenresList: Genre[];
  @Input() trackTypesList: any[];
  @Input() moodsList: IMood[];

  public isSend: boolean = false;
  public currentTab: number = 1;
  public saleStatus: boolean = false;
  public licensingStatus: boolean = false;
  public licensingEstatus: boolean = false;
  public nonProfitStatus: boolean = false;
  public neverSaleStatus: boolean = false;

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
      filename: this.uploadTrackInfo.file_name,
      track_id: this.uploadTrackInfo.track_id,
      waveform: '',
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
      pick_moods: this.uploadTrackInfo.genre_id,
      type_artist: this.uploadTrackInfo.type_artist,
      is_public: this.uploadTrackInfo.is_public,
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
    });

    this.uploadTrackForm.valueChanges
      .subscribe(data => {
        console.log(data);
        this.onValueChange(data);
      });
    this.onValueChange();
  }

  constructor(
    private _uploadService: UploadService,
    private fb: FormBuilder,
    private _localStorageService: LocalStorageService,
    private _helpersService: HelpersService,
    private _sharedService: SharedService
  ) {}

  ngOnInit() {
    this.uploadTrackInfo = this._uploadService.uploadTrackInfo;
    this.buildForm();

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
    //get local storage
    this.getLocalStorageSell();

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

    //TODO sound image
    if (this._uploadService.trackImage.file) {
      this.trackImage = `data:${this._uploadService.trackImage.file.format};base64,${base64ArrayBuffer(this._uploadService.trackImage.file.data)}`;
      this._uploadService.trackImage.file = this.trackImage;
      console.log(this._uploadService.trackImage);
    }
  }

  public getLocalStorageSell() {
    if(this._localStorageService.getLocalStorage('sellData')) {
      let sellData = JSON.parse(this._localStorageService.getLocalStorage('sellData'));

      for(let key in sellData) {
        if(sellData[key] !== '') {
          console.log();
          this.salePlaceholder[key] = sellData[key];
        }
      }
    }
  }

  public updatePrice(e) {
    this.sellData[e.id] = e.price;
    this._localStorageService.setLocalStorage({
      key: 'sellData',
      val: JSON.stringify(this.sellData)
    });
  }

  public checkedPrice(e) {
    if(!e.status) {
      this.sellData[e.id] = '';
    } else {
      this.sellData[e.id] = this.salePlaceholder[e.id] || e.price;
    }
  }

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

  public closePopup() {
    this._uploadService.editPopupSubject.next(false);
  }

  public onSubmit(e) {
    this.isSend = true;
    e.preventDefault();

    if(!this.nonProfitStatus) {
      let mergeResult = _.merge(this.uploadTrackForm.value, this.sellData);
      mergeResult.waveform = this._uploadService.uploadTrackInfo.waveform;
      this.uploadTrackForm.patchValue(mergeResult);
    } else {
      let mergeResult = _.merge(this.uploadTrackForm.value, this.sellData);
      mergeResult.waveform = this._uploadService.uploadTrackInfo.waveform;
      this.uploadTrackForm.patchValue(mergeResult);
    }

    //TODO lodash use
    let resultFormJSON = JSON.stringify(this.uploadTrackForm.value);
    let release_date = this.uploadTrackForm.value.release_date;
    let resultForm = JSON.parse(resultFormJSON);
    resultForm.release_date = JSON.stringify(release_date);
    let formData = this._helpersService.toStringParam((resultForm));

    //save track info
    this._uploadService.uploadTrackDetails(formData).subscribe(res => {
      if(res.hasOwnProperty('track_id')) {
        if(res.track_id != 0) {
          this._sharedService.notificationSubject.next({
            title: 'Save file',
            msg: 'Success save',
            type: 'success'
          });
          //upload image
          this.submitImage(res.track_id);
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
      this.isSend = false;
    });
  }

  public submitImage(trackId) {
    this._uploadService.trackImage.track_id = trackId;
    let req = this._helpersService.toStringParam(this._uploadService.trackImage);
    this._uploadService.uploadImageTrack(req).subscribe(res => {
      console.log(res);
      //update view
      this._uploadService.editPopupSubject.next(false);
    }, err => {
      console.log(err);
    })
  }

  public toggleTabs(tab) {
    this.currentTab = tab;
  }

  public uploadImage(event) {
    if (event.target.files && event.target.files[0]) {
      let reader = new FileReader();

      reader.onload = (event: any) => {
        //TODO upload image track
        this.trackImage = event.target.result;
        this._uploadService.trackImage.file = event.target.result;
        this._uploadService.trackImage.type = 'base64';
      };
      reader.readAsDataURL(event.target.files[0]);
    }
  }

  //second tabs
  public toggleSaleStatus() {
    this.saleStatus = !this.saleStatus;
    this.nonProfitStatus = false;
    this.neverSaleStatus = false;
    if(!this.saleStatus) {
      this._clearAllSale();
    } else {
      this._clearNonProfit();
    }
  }
  public toggleLicensingStatus() {
    this.licensingStatus = !this.licensingStatus;
    this.nonProfitStatus = false;
    this.neverSaleStatus = false;
    if(!this.licensingStatus) {
      this._clearAllLicensing();
    } else {
      this._clearNonProfit();
    }
  }
  public toggleLicensingEStatus() {
    this.licensingEstatus = !this.licensingEstatus;
    this.nonProfitStatus = false;
    this.neverSaleStatus = false;
    if(!this.licensingEstatus) {
      this._clearAllELicensing();
    } else {
      this._clearNonProfit();
    }
  }
  public toggleNonProfit() {
    this.nonProfitStatus = !this.nonProfitStatus;
    this.neverSaleStatus = false;
  }
  public toggleNeverSale() {
    this.neverSaleStatus = !this.neverSaleStatus;
    this.saleStatus = false;
    this.licensingStatus = false;
    this.licensingEstatus = false;
    this.nonProfitStatus = false;

    if(this.neverSaleStatus) {
      this._clearAllLicensing();
      this._clearAllELicensing();
      this._clearAllSale();
      this._clearNonProfit();
    }
  }

  private _clearAllLicensing() {
    this.sellData.advertising = '';
    this.sellData.corporate = '';
    this.sellData.documentaryFilm = '';
    this.sellData.film = '';
    this.sellData.software = '';
    this.sellData.internetVideo = '';
    this.sellData.liveEvent = '';
    this.sellData.musicHold = '';
    this.sellData.musicProd1k = '';
    this.sellData.musicProd10k = '';
    this.sellData.musicProd50k = '';
    this.sellData.musicProd51k = '';
    this.sellData.website = '';
  }
  private _clearAllELicensing() {
    this.sellData.advertisingE = '';
    this.sellData.corporateE = '';
    this.sellData.documentaryFilmE = '';
    this.sellData.filmE = '';
    this.sellData.softwareE = '';
    this.sellData.internetVideoE = '';
    this.sellData.liveEventE = '';
    this.sellData.musicHoldE = '';
    this.sellData.musicProd1kE = '';
    this.sellData.musicProd10kE = '';
    this.sellData.musicProd50kE = '';
    this.sellData.musicProd51kE = '';
    this.sellData.websiteE = '';
  }
  private _clearAllSale() {
    this.sellData.album = '';
    this.sellData.single = '';
  }
  private _clearNonProfit() {
    this.sellData.nonProfit = '';
  }

  showTest() {
    if(!this.nonProfitStatus) {
      let mergeResult = _.merge(this.uploadTrackForm.value, this.sellData);
      mergeResult.waveform = this._uploadService.uploadTrackInfo.waveform;
      this.uploadTrackForm.patchValue(mergeResult);
    } else {
      let mergeResult = _.merge(this.uploadTrackForm.value, this.sellData);
      mergeResult.waveform = this._uploadService.uploadTrackInfo.waveform;
      this.uploadTrackForm.patchValue(mergeResult);
    }

    //TODO(AlexSol): upload image (convert array base64 to file)
    let imageData = {
      filename: this._uploadService.uploadTrackInfo.file_name,
      image: this._uploadService.trackImage
    };

    this._uploadService.uploadImageTrack(imageData);
    console.log('imageData: ', imageData);
    console.log('uploadTrackForm.value: ', this.uploadTrackForm.value);
  }
}
