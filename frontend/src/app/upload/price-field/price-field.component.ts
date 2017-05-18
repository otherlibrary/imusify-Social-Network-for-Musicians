import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';

@Component({
  selector: 'app-price-field',
  templateUrl: './price-field.component.html',
  styleUrls: ['./price-field.component.scss']
})
export class PriceFieldComponent implements OnInit {
  @Input() price: string;
  @Input() id: string;
  @Input() checked: any;
  @Output() update: EventEmitter<any> = new EventEmitter<any>();
  @Output() checks: EventEmitter<any> = new EventEmitter<any>();

  public priceStatusCheck: boolean;
  public priceReady: boolean = false;

  constructor() { }

  ngOnInit() {
    this.priceStatusCheck = this.price != null;
  }

  toggleCheck(price) {
    this.priceStatusCheck = !this.priceStatusCheck;
    this.priceReady = false;

    this.checks.emit({
      id: this.id,
      status: this.priceStatusCheck,
      price: price
    });
  }

  togglePrice() {
    if(this.priceStatusCheck) {
      this.priceReady = !this.priceReady;
    }
  }

  changePrice(e) {
    this.price = e.target.value;
    this.update.emit({id: this.id, price: this.price});
  }
}
