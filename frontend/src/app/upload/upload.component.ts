import { Component, OnInit } from '@angular/core';
import { UploadService } from './upload.service';
import { Observable } from 'rxjs';
import {ActivatedRoute} from '@angular/router';

@Component({
  selector: 'app-upload',
  templateUrl: 'upload.component.html',
  styleUrls: ['upload.component.scss']
})
export class UploadComponent implements OnInit {
  public uploadInput: HTMLInputElement;

  constructor(
    public _uploadService: UploadService
  ) {}

  ngOnInit() {
    const musicUpload = window.document.getElementById('music-upload');
    this.uploadInput = (<HTMLInputElement>musicUpload);
    if (!this.instantiateInputListener(this.uploadInput)) {
      console.log('Upload input doesn\'t exist!');
    }
  }

  instantiateInputListener(input: HTMLInputElement): Boolean {
    const source = Observable.fromEvent(this.uploadInput, 'change');
    
    const randomStr = this._uploadService.generateRandomString();
    this._uploadService.uploadTrackInfo.r = randomStr;


    if (input) {
      source.subscribe((e: any) => {
        const file = e.target.files[0],
            size = file.size,
            name = file.name,
            type = file.type;

        this.inputChangeCallback(file);
      }, err => {
        console.log(err);
      });
      return true;
    } else {
      return false;
    }
  }

  inputChangeCallback(file): void {
    this._uploadService.uploadTrack(file).subscribe(data => {
      console.log(data);
    }, err => {
      console.log(err);
    });
  }

}
