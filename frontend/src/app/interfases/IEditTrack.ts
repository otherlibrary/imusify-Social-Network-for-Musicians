import {IMood} from "./IMood";
import {IGenre} from "app/interfases";
export interface IEditTrack {
  description: string;
  genre: string;
  genreId: string;
  isPublic: string;
  licences: Object
  moods: IMood[];
  release_dd: string;
  release_mm: string;
  release_yy: string;
  secondary_genres: IGenre[]
  title: string;
  trackId: string;
  track_musician_type: string;
  trackuploadType: string;
}