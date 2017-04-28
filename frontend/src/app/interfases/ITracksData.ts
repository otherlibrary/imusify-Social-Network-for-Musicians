import {IRecord} from "./IRecord";
import {IUser} from "./IUser";

interface optionsDataArray {
  data_array?: IRecord[]
  records?: IRecord[]
}

export interface ITracksData extends optionsDataArray, IUser {
  img?: string
}
