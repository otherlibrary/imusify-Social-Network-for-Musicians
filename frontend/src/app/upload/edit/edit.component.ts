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
    this._uploadService.uploadTrackInfo.title = 'test track';
    this._uploadService.uploadTrackInfo.desc = 'test desk';
    this._uploadService.uploadTrackInfo.mm = '03';
    this._uploadService.uploadTrackInfo.dd = '31';
    this._uploadService.uploadTrackInfo.yy = '2017';
    this._uploadService.uploadTrackInfo.trackuploadtype = 2;
    this._uploadService.uploadTrackInfo.genre = 5;
    this._uploadService.uploadTrackInfo.ispublic = 'y';
    this._uploadService.uploadTrackInfo.sale_available = 1;
    this._uploadService.uploadTrackInfo['sale_available_ar[]'] = 1;
    this._uploadService.uploadTrackInfo.sell_1 = 0.99;
    this._uploadService.uploadTrackInfo.sell_2 = 0.99;
    this._uploadService.uploadTrackInfo.licence_available = 1;
    this._uploadService.uploadTrackInfo.lic_13 = 5.00;
    this._uploadService.uploadTrackInfo['licence_available_ar[]'] = 12;
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
