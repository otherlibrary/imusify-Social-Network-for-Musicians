import {IRecord} from "./IRecord";

export interface IRecordEvent {
  type: string;
  record: IRecord;
}