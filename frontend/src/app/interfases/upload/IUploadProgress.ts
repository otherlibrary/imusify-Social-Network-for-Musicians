import {UploadStatus} from "ngx-uploader";

export interface UploadProgress {
  status: UploadStatus;
  data?: {
    percentage: number;
    speed: number;
    speedHuman: string;
  };
}