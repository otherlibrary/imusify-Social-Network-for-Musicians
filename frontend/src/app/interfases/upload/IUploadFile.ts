import {UploadProgress} from "./IUploadProgress";

export interface UploadFile {
  id: string;
  fileIndex: number;
  lastModifiedDate: Date;
  name: string;
  size: number;
  type: string;
  progress: UploadProgress;
  response?: any;
}