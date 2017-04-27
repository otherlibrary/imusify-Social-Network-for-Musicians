import {Component, OnInit} from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {UploadService} from '../upload.service';
import 'rxjs/add/operator/switchMap';

@Component({
  selector: 'app-edit',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.scss']
})

export class EditComponent implements OnInit {
  public track: any;

  constructor(
    private _router: Router,
    private _route: ActivatedRoute,
    private _uploadService: UploadService
  ) {}

  ngOnInit() {
    this._route.params
      .switchMap((params: Params) => this._uploadService.getTrackById(params['id']))
      .subscribe((track) => {
        this.track = track;
        console.log(track);
      });
  }

  //TODO(AlexSol): refactoring
  uploadTrackImage(event: any) {
    const file = event.target.files[0],
          type = file.type;
    if (file) {
      const reader = new FileReader();

      reader.onload = (readerEvt: any) => {
        const binaryString = readerEvt.target.result;
        const img = window.btoa(binaryString);

        this._uploadService.uploadTrackImage(img, type).subscribe(data => {
          this._uploadService.uploadTrackInfo.cover_base64_uploaded = data.url;
        }, err => {
          console.log(err);
        });
      };
      reader.readAsBinaryString(file);
    }
  }

  saveUploadTrack() {
    this._uploadService.uploadTrackInfo.tracktype = 'mp3';
    this._uploadService.uploadTrackInfo.title = 'Delirium';
    this._uploadService.uploadTrackInfo.cover_base64_uploaded = 'https://beta.imusify.com/imusify/assets/upload/track/570773fac83121ec38c3733fb801b026945031886.png';
    this._uploadService.uploadTrackInfo.desc = '';
    this._uploadService.uploadTrackInfo.mm = '04';
    this._uploadService.uploadTrackInfo.dd = '27';
    this._uploadService.uploadTrackInfo.yy = '2017';
    this._uploadService.uploadTrackInfo.trackuploadtype = 1;
    this._uploadService.uploadTrackInfo.genre = 1;
    this._uploadService.uploadTrackInfo.ispublic = 'y';
    this._uploadService.uploadTrackInfo['sec_genner[]'] = '29';
    this._uploadService.uploadTrackInfo['sound_like[]'] = '5644';
    this._uploadService.uploadTrackInfo['moods_list[]'] = '5';
    this._uploadService.uploadTrackInfo['instruments[]'] = '2';
    this._uploadService.uploadTrackInfo.sale_available = 1;
    this._uploadService.uploadTrackInfo.add_album = 'Add to album';
    this._uploadService.uploadTrackInfo.trackuploadbpm = 180;
    // this._uploadService.uploadTrackInfo['sale_available_ar[]'] = 1;
    this._uploadService.uploadTrackInfo.sell_1 = '';
    this._uploadService.uploadTrackInfo.sell_2 = '';
    this._uploadService.uploadTrackInfo.licence_available = 1;
    this._uploadService.uploadTrackInfo.lic_13 = '';
    this._uploadService.uploadTrackInfo.lic_14 = '';
    this._uploadService.uploadTrackInfo.license_number_14 = '';
    this._uploadService.uploadTrackInfo.lic_13 = '';
    this._uploadService.uploadTrackInfo.license_number_13 = '';
    this._uploadService.uploadTrackInfo.lic_10 = '';
    this._uploadService.uploadTrackInfo.license_number_10 = '';
    this._uploadService.uploadTrackInfo.lic_9 = '';
    this._uploadService.uploadTrackInfo.license_number_9 = '';
    this._uploadService.uploadTrackInfo.lic_8 = '';
    this._uploadService.uploadTrackInfo.license_number_8 = '';
    this._uploadService.uploadTrackInfo.lic_16 = '';
    this._uploadService.uploadTrackInfo.license_number_16 = '';
    this._uploadService.uploadTrackInfo.lic_11 = '';
    this._uploadService.uploadTrackInfo.license_number_11 = '';
    this._uploadService.uploadTrackInfo.lic_17 = '';
    this._uploadService.uploadTrackInfo.license_number_17 = '';
    this._uploadService.uploadTrackInfo.lic_15 = '';
    this._uploadService.uploadTrackInfo.license_number_15 = '';
    this._uploadService.uploadTrackInfo.lic_4 = '';
    this._uploadService.uploadTrackInfo.license_number_4 = '';
    this._uploadService.uploadTrackInfo.lic_18 = '';
    this._uploadService.uploadTrackInfo.license_number_18 = '';
    this._uploadService.uploadTrackInfo.lic_12 = '';
    this._uploadService.uploadTrackInfo.license_number_12 = '';
    this._uploadService.uploadTrackInfo.el_26 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_26 = '';
    this._uploadService.uploadTrackInfo.el_25 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_25 = '';
    this._uploadService.uploadTrackInfo.el_22 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_22 = '';
    this._uploadService.uploadTrackInfo.el_21 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_21 = '';
    this._uploadService.uploadTrackInfo.el_20 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_20 = '';
    this._uploadService.uploadTrackInfo.el_28 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_28 = '';
    this._uploadService.uploadTrackInfo.el_23 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_23 = '';
    this._uploadService.uploadTrackInfo.el_29 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_29 = '';
    this._uploadService.uploadTrackInfo.el_27 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_27 = '';
    this._uploadService.uploadTrackInfo.el_31 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_31 = '';
    this._uploadService.uploadTrackInfo.el_30 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_30 = '';
    this._uploadService.uploadTrackInfo.el_24 = '';
    this._uploadService.uploadTrackInfo.exclusive_number_24 = '';
    this._uploadService.uploadTrackInfo.np_19 = '';
    // this._uploadService.uploadTrackInfo['licence_available_ar[]'] = 12;
    console.log(this._uploadService.uploadTrackInfo);
    this._uploadService.saveTrack().subscribe(data => {
      console.log(data);
    }, err => {
      console.log(err);
    });
  }

  closePopup() {
    this._router.navigate(['upload']);
  }
}
