import {UploadFileData} from "../interfases";
export class UploadTrackInfo implements UploadFileData {
  track_id: string;
  title: string;
  filename: string;
  desc?: string;
  genre_id: number;
  is_public: number;
  track_upload_type: string;
  track_upload_bpm: string;
  track_type: number;
  music_vocals_y: string;
  music_vocals_gender: string;
  sale_available: string;
  licence_available: string;
  nonprofit_available: string;
  release_date: string;
}