import {Directive, Input, OnInit, ViewContainerRef} from '@angular/core';

@Directive({
  selector: '[appAudioSlider]'
})
export class AudioSliderDirective implements OnInit {
  @Input() appAudioSlider;

  constructor(
    private viewContainerRef: ViewContainerRef
  ) {}

  ngOnInit() {
    // if(this.appAudioSlider) {
    //   console.log(this.viewContainerRef.element.nativeElement.previousElementSibling);
    //   this.viewContainerRef.element.nativeElement.previousElementSibling.classList.add('next-slide');
    // }
  }
  //
  // ngOnChanges(data) {
  //   if(this.appAudioSlider) {
  //     console.log(this.viewContainerRef.element.nativeElement.previousElementSibling);
  //     this.viewContainerRef.element.nativeElement.previousElementSibling.classList.add('next-slide');
  //   }
  // }
}
