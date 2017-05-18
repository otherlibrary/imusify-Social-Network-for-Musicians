import {IMood} from "./IMood";
import {IGenre} from "app/interfases";
export interface IEditTrack {
  description: string;
  genre: string;
  genreId: number;
  isPublic: string;
  licences: any[];
  moods: any[];
  release_dd: string;
  release_mm: string;
  release_yy: string;
  secondary_genres: any[];
  title: string;
  trackId: string;
  track_musician_type: string;
  trackuploadType: string;
  typeArtist: string;
}