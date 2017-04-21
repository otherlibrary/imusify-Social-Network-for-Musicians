import {Component, OnInit, OnDestroy} from '@angular/core';
import {AppComponent} from "../../app.component";

@Component({
    selector: 'app-checkout',
    templateUrl: './checkout.component.html',
    styleUrls: ['./checkout.component.scss']
})
export class CheckoutComponent implements OnInit, OnDestroy {

    constructor(private rootComp: AppComponent) {
    }

    ngOnInit() {
        this.rootComp.cssClass = 'full-width';
    }
    ngOnDestroy() {
        this.rootComp.cssClass = '';
    }
}
