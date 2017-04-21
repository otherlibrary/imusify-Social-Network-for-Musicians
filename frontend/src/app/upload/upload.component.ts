import { Component, OnInit } from '@angular/core';
import { UploadService } from './upload.service';
import { Observable } from 'rxjs';
import {QueueService} from '../shared/services/queue.service';
import {INewTrack} from '../interfases/new-track';
import {ActivatedRoute, Router} from '@angular/router';

@Component({
  selector: 'app-upload',
  templateUrl: 'upload.component.html',
  styleUrls: ['upload.component.scss'],
  providers: [QueueService]
})
export class UploadComponent implements OnInit {
  public uploadInput: HTMLInputElement;
  private _maxChunkSize: number = 1048576;
  public newTrack: INewTrack;

  constructor(
    public _uploadService: UploadService,
    private _queueService: QueueService,
    private _router: Router,
    private _route: ActivatedRoute
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
            type = file.type,
            blob =  this.generatePartialObjectFromFile(file);
        let start = 0,
            end = this._maxChunkSize;

        while (start < size) {
            const callback = this.inputChangeCallback.bind(
              this,
              blob.slice(start, end, type),
              name,
              randomStr
            );
            this._queueService.add(callback);
            start = end;
            end = start + this._maxChunkSize;
        }
        this._queueService.add(function(){
          this._router.navigate(['edit/10'], { relativeTo: this._route });
        });
        this._queueService.iterate(this);
      }, err => {
        console.log(err);
      });
      return true;
    } else {
      return false;
    }
  }

  inputChangeCallback(chunk: Blob, name: string, randomStr): void {
    this._uploadService.uploadTrack(chunk, name, randomStr).subscribe(data => {
      this._queueService.iterate(this);
      console.log(data);
    }, err => {
      console.log(err);
    });
  }

  generatePartialObjectFromFile(file: File): Blob {
    return new Blob([file], {type: file.type});
  }

}
