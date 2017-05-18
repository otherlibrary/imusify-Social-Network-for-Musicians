import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'licenseType'
})
export class LicenseTypePipe implements PipeTransform {

  transform(items: any[], filter: string): any {
    return items.filter(item => {
      return item.lic_type === filter;
    });
  }

}
