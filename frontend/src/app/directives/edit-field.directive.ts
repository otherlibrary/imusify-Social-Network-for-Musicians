import {Directive, HostBinding, HostListener, Input} from '@angular/core';

@Directive({
  selector: '[appEditField]'
})
export class EditFieldDirective {
  @Input()
  appEditField: string;

  constructor() { }
  //@HostBinding() innerText = 'test';
  @HostListener('click') onClick() {
    // this.highlight(this.highlightColor || this.defaultColor || 'red');
  }
}
