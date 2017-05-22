import {Component, EventEmitter, OnInit} from '@angular/core';
import {UploadInput, UploadOutput, UploadFile, IToastOption} from "../interfases";
import {Observable} from "rxjs/Observable";
import {UploadService} from "./upload.service";
import {environment} from "../../environments/environment";
import {ToastyConfig} from "ng2-toasty";
import {SharedService} from "../shared/shared.service";
import {UploadTrackInfo} from "../models/UploadTrackInfo";

declare const jsmediatags: any;
declare const WaveSurfer: any;

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

export class UploadComponent implements OnInit {
  public formData: FormData;
  public files: UploadFile[];
  public fileId: string;
  public uploadInput: EventEmitter<UploadInput>;
  public dragOver: boolean;
  private host: {};

  private _cutNameExtension(name: string): string {
    return name.match(/(.*)\.[^.]+$/)[1];
  };

  constructor(private _uploadService: UploadService,
              private _sharedService: SharedService,
              private _toastyConfig: ToastyConfig) {
    this.host = environment.host;

    this._toastyConfig.theme = 'material';

    this.formData = {
      concurrency: 0,
      autoUpload: true,
      verbose: true
    };

    this.files = [];
    this.fileId = '';
    this.uploadInput = new EventEmitter<UploadInput>();
  }

  ngOnInit() {
    //init waveform
    if (!this._uploadService.wavesurfer) {
      this._uploadService.wavesurfer = WaveSurfer.create({
        container: '#waveform2',
        backend: 'MediaElement',
        pixelRatio: 1,
        normalize: true
      });

      this._uploadService.wavesurfer.on('waveform-ready', (e) => {
        this._uploadService.uploadTrackInfo.waveform = this._uploadService.wavesurfer.backend.mergedPeaks;
      });
    }
  }

  public getTagsFile(e) {
    this._uploadService.clearUploadTrackInfo();

    let files = e.target.files;
    let file = files[files.length - 1];
    let fileURL = URL.createObjectURL(file);
    this._uploadService.wavesurfer.empty();
    this._uploadService.wavesurfer.load(fileURL);
    this._uploadService.trackImage.file = null;

    jsmediatags.read(file, {
      onSuccess: (tag) => {
        let tags = tag.tags;
       this._uploadService.trackImage.type = 'base64';
        if ("picture" in tags) {
         this._uploadService.trackImage.file = tags.picture;
        } else {
          this._uploadService.trackImage.file = null;
        }
      },
      onError: (error) => {
        console.log(':(', error.type, error.info);
      }
    });
  }

  public onUploadOutput(output: UploadOutput): void {
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

      if (file.response.hasOwnProperty('upload_data')) {
        this._uploadService.uploadTrackInfo.file_name = file.response.upload_data.file_name;
        this._uploadService.uploadTrackInfo.title = this._cutNameExtension(file.name);
        this._uploadService.uploadTrackInfo.track_id = file.id;

        let t = Observable.timer(300).subscribe(() => {
          this._uploadService.editPopupSubject.next(true);
          t.unsubscribe();
        });

        //notification
        this._sharedService.notificationSubject.next({
          title: 'Upload file',
          msg: 'Success upload',
          type: 'success'
        });
      } else {
        if (file.response.hasOwnProperty('error')) {
          this._sharedService.notificationSubject.next({
            title: 'Error upload file',
            msg: file.response.error,
            type: 'error'
          });
        }
      }
    } else if (output.type === 'cancelled') {
      console.log('cancelled');
    } else if (output.type === 'start') {
      console.log('start');
    }
  }

  public startUpload(): void {
    const event: UploadInput = {
      type: 'uploadAll',
      url: environment.host + environment.uploadFilesUrl,
      method: 'POST',
      data: {foo: 'random'}
    };

    this.uploadInput.emit(event);
  }

  public cancelUpload(id: string): void {
    this.uploadInput.emit({type: 'cancel', id: id});
  }

}
