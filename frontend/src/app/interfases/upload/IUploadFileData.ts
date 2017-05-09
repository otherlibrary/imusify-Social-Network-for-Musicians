export interface UploadFileData {
  track_id: string;
  title: string;
  file_name: string;
  release_date: string;
  track_type: number;
  genre_id: number;
  desc?: string;
  secondary_genre_id?: number;
  pick_moods_id?: number;
  waveform: any;
  type_artist: string;
  copyright: boolean;

  is_public: number;
  track_upload_type: string;
  track_upload_bpm: string;
  music_vocals_y: string;
  music_vocals_gender: string;
  sale_available: string;
  licence_available: string;
  nonprofit_available: string;
}