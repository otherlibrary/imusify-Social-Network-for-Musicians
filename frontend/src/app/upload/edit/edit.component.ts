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
  @Input() editTrack: any;

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
  public cacheSellData: any;

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

    this.setEditData();
  }

  public getLocalStorageSell() {
    if(this._localStorageService.getLocalStorage('sellData')) {
       this.cacheSellData = JSON.parse(this._localStorageService.getLocalStorage('sellData'));
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
      this.sellData[e.id] = e.price;
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
      this._checkAllSale();
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
      this._checkAllLicensing();
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
      this._checkAllELicensing();
      this._clearNonProfit();
    }
  }
  public toggleNonProfit() {
    this.nonProfitStatus = !this.nonProfitStatus;
    this.neverSaleStatus = false;
    if(this.nonProfitStatus) {
      this._checkNonProfit();
    } else {
      this._clearNonProfit();
    }
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

  private _clearAllSale() {
    this.sellData.album = '';
    this.sellData.single = '';
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
  private _clearNonProfit() {
    this.sellData.nonProfit = '';
  }

  private _checkAllSale() {
    this.sellData.album = this.cacheSellData.album || '9.99';
    this.sellData.single = this.cacheSellData.single || '0.99';
  }
  private _checkAllLicensing() {
    this.sellData.advertising = this.cacheSellData.advertising || '225';
    this.sellData.corporate = this.cacheSellData.corporate || '495';
    this.sellData.documentaryFilm = this.cacheSellData.documentaryFilm || '60';
    this.sellData.film = this.cacheSellData.film || '120';
    this.sellData.software = this.cacheSellData.software || '300';
    this.sellData.internetVideo = this.cacheSellData.internetVideo || '3';
    this.sellData.liveEvent = this.cacheSellData.liveEvent || '15';
    this.sellData.musicHold = this.cacheSellData.musicHold || '30';
    this.sellData.musicProd1k = this.cacheSellData.musicProd1k || '35';
    this.sellData.musicProd10k = this.cacheSellData.musicProd10k || '105';
    this.sellData.musicProd50k = this.cacheSellData.musicProd50k || '175';
    this.sellData.musicProd51k = this.cacheSellData.musicProd51k || '245';
    this.sellData.website = this.cacheSellData.website || '5';
  }
  private _checkAllELicensing() {
    this.sellData.advertisingE = this.cacheSellData.advertisingE || '2250';
    this.sellData.corporateE = this.cacheSellData.corporateE || '4950';
    this.sellData.documentaryFilmE = this.cacheSellData.documentaryFilmE || '600';
    this.sellData.filmE = this.cacheSellData.filmE || '1200';
    this.sellData.softwareE = this.cacheSellData.softwareE || '3000';
    this.sellData.internetVideoE = this.cacheSellData.internetVideoE || '30';
    this.sellData.liveEventE = this.cacheSellData.liveEventE || '150';
    this.sellData.musicHoldE = this.cacheSellData.musicHoldE || '300';
    this.sellData.musicProd1kE = this.cacheSellData.musicProd1kE || '350';
    this.sellData.musicProd10kE = this.cacheSellData.musicProd10kE || '1050';
    this.sellData.musicProd50kE = this.cacheSellData.musicProd50kE || '1750';
    this.sellData.musicProd51kE = this.cacheSellData.musicProd51kE || '2450';
    this.sellData.websiteE = this.cacheSellData.websiteE || '50';
  }
  private _checkNonProfit() {
    this.sellData.nonProfit = this.cacheSellData.nonProfit || '0';
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

  public setEditData() {
    if(this.editTrack) {
      this.uploadTrackForm.patchValue({
        filename: this.editTrack.filename,
        track_id: this.editTrack.track_id,
        waveform: this.editTrack.waveform,
        title: this.editTrack.title,
        desc: this.editTrack.desc,
        release_date: this.editTrack.release_date,
        track_type: this.editTrack.track_type,
        genre_id: this.editTrack.genre_id,
        copyright: this.editTrack.copyright,
        second_genre_id: this.editTrack.second_genre_id,
        pick_moods: this.editTrack.pick_moods,
        type_artist: this.editTrack.type_artist,
        is_public: this.editTrack.is_public,
        album: this.editTrack.album,
        single: this.editTrack.single,
        advertising: this.editTrack.advertising,
        corporate: this.editTrack.corporate,
        documentaryFilm: this.editTrack.documentaryFilm,
        film: this.editTrack.film,
        software: this.editTrack.software,
        internetVideo: this.editTrack.internetVideo,
        liveEvent: this.editTrack.liveEvent,
        musicHold: this.editTrack.musicHold,
        musicProd1k: this.editTrack.musicProd1k,
        musicProd10k: this.editTrack.musicProd10k,
        musicProd50k: this.editTrack.musicProd50k,
        musicProd51k: this.editTrack.musicProd51k,
        website: this.editTrack.website,
        advertisingE: this.editTrack.advertisingE,
        corporateE: this.editTrack.corporateE,
        documentaryFilmE: this.editTrack.documentaryFilmE,
        filmE: this.editTrack.filmE,
        softwareE: this.editTrack.softwareE,
        internetVideoE: this.editTrack.internetVideoE,
        liveEventE: this.editTrack.liveEventE,
        musicHoldE: this.editTrack.musicHoldE,
        musicProd1kE: this.editTrack.musicProd1kE,
        musicProd10kE: this.editTrack.musicProd10kE,
        musicProd50kE: this.editTrack.musicProd50kE,
        musicProd51kE: this.editTrack.musicProd51kE,
        websiteE: this.editTrack.websiteE,
        nonProfit: this.editTrack.nonProfit,
        neverSale: this.editTrack.neverSale
      });
    }
  }
}
