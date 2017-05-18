import {Injectable} from '@angular/core';

@Injectable()
export class HelpersService {

  constructor() {
  }

  public toStringParam(obj) {
    let str = "";
    for (let key in obj) {
      if (str != "") {
        str += "&";
      }
      str += key + "=" + encodeURIComponent(obj[key]);
    }
    return str;
  }

  public formatDate(date) {
    let d = new Date(date),
      month = '' + (d.getMonth() + 1),
      day = '' + d.getDate(),
      year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
  }

  public shuffle(array) {
    let currentIndex = array.length, temporaryValue, randomIndex;
    while (0 !== currentIndex) {

      randomIndex = Math.floor(Math.random() * currentIndex);
      currentIndex -= 1;

      temporaryValue = array[currentIndex];
      array[currentIndex] = array[randomIndex];
      array[randomIndex] = temporaryValue;
    }

    return array;
  }

  public move(array, fromIndex, toIndex) {
    array.splice(toIndex, 0, array.splice(fromIndex, 1)[0]);
  }
}
