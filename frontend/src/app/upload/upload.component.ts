import { Component, OnInit, EventEmitter } from '@angular/core';
import {UploadFile} from "../interfases/upload/IUploadFile";
import {UploadInput} from "../interfases/upload/IUploadInput";
import {UploadOutput} from "../interfases/upload/IUploadOutput";
import {ActivatedRoute, Router} from "@angular/router";
import {Observable} from "rxjs/Observable";

interface FormData {
  concurrency: number;
  autoUpload: boolean;
  verbose: boolean;
}

@Component({
  selector: 'app-upload',
  templateUrl: 'upload.component.html',
  styleUrls: ['upload.component.scss']
})
export class UploadComponent {
  formData: FormData;
  files: UploadFile[];
  uploadInput: EventEmitter<UploadInput>;
  dragOver: boolean;


  constructor(
    private _router: Router,
    private _route: ActivatedRoute
  ) {
    this.formData = {
      concurrency: 0,
      autoUpload: true,
      verbose: true
    };

    this.files = [];
    this.uploadInput = new EventEmitter<UploadInput>();
  }

  onUploadOutput(output: UploadOutput): void {
    if (output.type === 'allAddedToQueue') {
      if (this.formData.autoUpload) {
        const event: UploadInput = {
          type: 'uploadAll',
          url: '/api/track-upload/upload-track-file',
          method: 'POST',
          data: { foo: 'random' }
        };

        this.uploadInput.emit(event);
      }
    } else if (output.type === 'addedToQueue') {
      this.files.push(output.file);
    } else if (output.type === 'uploading') {
      const index = this.files.findIndex(file => file.id === output.file.id);
      this.files[index] = output.file;
    } else if (output.type === 'removed') {
      this.files = this.files.filter((file: UploadFile) => file !== output.file);
    } else if (output.type === 'dragOver') {
      this.dragOver = true;
    } else if (output.type === 'dragOut') {
      this.dragOver = false;
    } else if (output.type === 'drop') {
      this.dragOver = false;
    } else if(output.type === 'done') {
      let t = Observable.timer(500).subscribe(() => {
        this._router.navigate(['edit/10'], { relativeTo: this._route });
        t.unsubscribe();
      });
    } else if(output.type === 'cancelled') {
      console.log('cancelled');
    } else if(output.type === 'start') {
      console.log('start');
    }
  }

  startUpload(): void {
    const event: UploadInput = {
      type: 'uploadAll',
      url: '/api/track-upload/upload-track-file',
      method: 'POST',
      data: { foo: 'random' }
    };

    this.uploadInput.emit(event);
  }

  cancelUpload(id: string): void {
    this.uploadInput.emit({ type: 'cancel', id: id });
  }

}
