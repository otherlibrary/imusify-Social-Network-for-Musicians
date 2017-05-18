import {IGenre} from "../IGenre";
import {Like} from "../ILike";
import {IRecord} from "../IRecord";

export interface UploadAudioData {
  user_id?: string;
  genre_list: IGenre[];
  sec_genre_list: IGenre[];
  sound_like_list: Like[];
  never_sell: string;
  loadmore: boolean;
  data_array?: IRecord[]
}