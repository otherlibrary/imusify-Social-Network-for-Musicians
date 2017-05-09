import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';

@Component({
  selector: 'app-price-field',
  templateUrl: './price-field.component.html',
  styleUrls: ['./price-field.component.scss']
})
export class PriceFieldComponent implements OnInit {
  @Input() price: string;
  @Input() id: string;
  @Input() placeholder: string;
  @Output() update: EventEmitter<any> = new EventEmitter<any>();
  @Output() checks: EventEmitter<any> = new EventEmitter<any>();

  public priceStatusCheck: boolean = false;
  public priceReady: boolean = false;

  constructor() { }

  ngOnInit() {
  }

  toggleCheck(price) {
    this.priceStatusCheck = !this.priceStatusCheck;
    this.priceReady = false;

    this.checks.emit({
      id: this.id,
      status: this.priceStatusCheck,
      price: price
    });
    console.log(price);
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
