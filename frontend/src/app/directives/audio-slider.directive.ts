import {Directive, ElementRef, Input, OnInit, ViewChild, ViewContainerRef} from '@angular/core';

@Directive({
  selector: '[appAudioSlider]'
})
export class AudioSliderDirective implements OnInit {
  @Input() appAudioSlider;
  private _elements: any;
  private _countSlides: 6;

  constructor(
    private viewContainerRef: ViewContainerRef
  ) {}

  ngOnInit() {
     this._elements = document.querySelectorAll('.big-block__img');
  }

  private _removeClasses(item: HTMLElement, cls: string[]): void {
    cls.map((clsItem: string) => {
      item.classList.remove(clsItem);
    })
  }

  ngOnChanges() {
    if(this.appAudioSlider) {

      if(this._elements) {
        this._elements.forEach(item => {
          this._removeClasses(item, ['slide-1', 'slide-2', 'slide-3'])
        });
      }

      //for(let i = 0)
      let nativeEl = this.viewContainerRef.element.nativeElement;
      if(nativeEl.nextElementSibling) {
        nativeEl.nextElementSibling.classList.add('slide-1');

        if(nativeEl.nextElementSibling.nextElementSibling) {
          nativeEl.nextElementSibling.nextElementSibling.classList.add('slide-2');

          if(nativeEl.nextElementSibling.nextElementSibling.nextElementSibling) {
            nativeEl.nextElementSibling.nextElementSibling.nextElementSibling.classList.add('slide-3');
          }
        } else {
          console.log(1);
        }
      }
      if(nativeEl.previousElementSibling) {
        nativeEl.previousElementSibling.classList.add('slide-1');

        if(nativeEl.previousElementSibling.previousElementSibling) {
          nativeEl.previousElementSibling.previousElementSibling.classList.add('slide-2');

          if(nativeEl.previousElementSibling.previousElementSibling.previousElementSibling) {
            nativeEl.previousElementSibling.previousElementSibling.previousElementSibling.classList.add('slide-3');
          }
        }
      }







    }
  }
}
