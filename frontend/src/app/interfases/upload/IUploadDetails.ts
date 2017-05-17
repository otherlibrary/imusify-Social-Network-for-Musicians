import {Genre} from "../IGenre";
import {Like} from "../ILike";
import {IMood} from "../IMood";
import {IItemDetails} from "./IItemDetails";



export interface IUploadDetails {
  el_type_list: IItemDetails[];
  genre: Genre[];
  higher_type_list: string[];
  instuments_list: IItemDetails[];
  licence_type_list: IItemDetails[];
  lower_type_list: string[];
  mood_list: IMood[];
  np_type_list: IItemDetails[];
  sec_genre: IItemDetails[];
  sell_type_list: IItemDetails[];
  sound_like_list: Like[];
  track_upload_type_list: IItemDetails[];
}