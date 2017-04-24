import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'timeFormat'
})
export class TimeFormatPipe implements PipeTransform {

  transform(value: any, args?: any): any {
    if (!value) {
      return "00:00";
    }
    var minutes = Math.floor(value / 60);
    var seconds = Math.ceil(value) % 60;

    return (minutes < 10 ? '0' : '')
      + minutes
      + ":"
      + (seconds < 10 ? '0' : '') + seconds;
  }
}
