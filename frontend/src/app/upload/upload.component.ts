import {Component, EventEmitter} from '@angular/core';
import {UploadInput, UploadOutput, UploadFile, UploadFileData} from "../interfases";
import {ActivatedRoute, Router} from "@angular/router";
import {Observable} from "rxjs/Observable";
import {UploadService} from "./upload.service";
import {environment} from "../../environments/environment";
declare const jsmediatags: any;

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
  public formData: FormData;
  public files: UploadFile[];
  public fileId: string;
  public uploadInput: EventEmitter<UploadInput>;
  public dragOver: boolean;
  public uploadTrackInfo: UploadFileData;


  private host: {};

  private cutNameExtension(name: string): string {
    return name.match(/(.*)\.[^.]+$/)[1];
  };

  constructor(
    private _router: Router,
    private _route: ActivatedRoute,
    private _UploadService: UploadService
  ) {
    this.host = environment.host;

    this.formData = {
      concurrency: 0,
      autoUpload: true,
      verbose: true
    };

    this.files = [];
    this.fileId = '';
    this.uploadInput = new EventEmitter<UploadInput>();
  }

  getTagsFile(e) {
    let files = e.target.files;
    let file = files[files.length - 1];

    jsmediatags.read(file, {
      onSuccess: (tag) => {
        this._UploadService.trackImage = tag.tags.picture;
      },
      onError: (error) => {
        console.log(':(', error.type, error.info);
      }
    });
  }

  onUploadOutput(output: UploadOutput): void {
    if (output.type === 'allAddedToQueue') {
      if (this.formData.autoUpload) {
        const event: UploadInput = {
          type: 'uploadAll',
          url: environment.host + environment.uploadFilesUrl,
          method: 'POST',
          data: {track_id: this.fileId}
        };
        this.uploadInput.emit(event);
      }
    } else if (output.type === 'addedToQueue') {
      this.fileId = output.file.id;
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
    } else if (output.type === 'done') {
      let file = this.files[this.files.length - 1];

      this._UploadService.uploadTrackInfo.title = this.cutNameExtension(file.name);
      this._UploadService.uploadTrackInfo.track_id = file.id;
      this._UploadService.uploadTrackInfo.filename = file.name;

      let t = Observable.timer(500).subscribe(() => {
        this._router.navigate(['edit/' + file.id], {relativeTo: this._route});
        t.unsubscribe();
      });
    } else if (output.type === 'cancelled') {
      console.log('cancelled');
    } else if (output.type === 'start') {
      console.log('start');
    }
  }

  startUpload(): void {
    const event: UploadInput = {
      type: 'uploadAll',
      url: environment.host + environment.uploadFilesUrl,
      method: 'POST',
      data: {foo: 'random'}
    };

    this.uploadInput.emit(event);
  }

  cancelUpload(id: string): void {
    this.uploadInput.emit({type: 'cancel', id: id});
  }

}
