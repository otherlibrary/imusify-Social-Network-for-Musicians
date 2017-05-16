import {Genre} from "../IGenre";
import {Like} from "../ILike";
import {IRecord} from "../IRecord";

export interface UploadAudioData {
  user_id?: string;
  genre_list: Genre[];
  sec_genre_list: Genre[];
  sound_like_list: Like[];
  never_sell: string;
  loadmore: boolean;
  data_array?: IRecord[]
}