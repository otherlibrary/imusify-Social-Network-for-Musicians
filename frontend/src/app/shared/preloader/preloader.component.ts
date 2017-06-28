import {Component, OnInit, OnChanges, trigger, state, transition, style, animate, Input} from '@angular/core';

@Component({
    selector: 'app-preloader',
    templateUrl: 'preloader.component.html',
    styleUrls: ['preloader.component.scss'],
    // animations: [
    //     trigger('visibilityChanged', [
    //         state('shown', style({opacity: 1, 'pointer-events': 'auto'})),
    //         state('hidden', style({opacity: 0, 'pointer-events': 'none'})),
    //         transition('* => *', animate('.3s'))
    //     ])
    // ]
})
export class PreloaderComponent implements OnInit {
    @Input() isVisible: boolean = false;
    visibility = 'hidden';

    constructor() {
    }

    ngOnInit() {
    }

    ngOnChanges() {
        this.visibility = this.isVisible ? 'shown' : 'hidden';
    }
}
