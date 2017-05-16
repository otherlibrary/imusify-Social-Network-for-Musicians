import {UploadFileData} from "../interfases";
export class UploadTrackInfo implements UploadFileData {
  track_id: string;
  title: string;
  file_name: string;
  desc?: string;
  waveform: string;
  copyright: boolean;
  genre_id: number;
  is_public: string;
  track_upload_type: string;
  track_upload_bpm: string;
  track_type: number;
  type_artist: string;
  music_vocals_y: string;
  music_vocals_gender: string;
  sale_available: string;
  licence_available: string;
  nonprofit_available: string;
  release_date: any;
  album: string;
  single: string;
  advertising: string;
  corporate: string;
  documentaryFilm: string;
  film: string;
  software: string;
  internetVideo: string;
  liveEvent: string;
  musicHold: string;
  musicProd1k: string;
  musicProd10k: string;
  musicProd50k: string;
  musicProd51k: string;
  website: string;
  advertisingE: string;
  corporateE: string;
  documentaryFilmE: string;
  filmE: string;
  softwareE: string;
  internetVideoE: string;
  liveEventE: string;
  musicHoldE: string;
  musicProd1kE: string;
  musicProd10kE: string;
  musicProd50kE: string;
  musicProd51kE: string;
  websiteE: string;
  nonProfit: string;
  neverSale: string;
}