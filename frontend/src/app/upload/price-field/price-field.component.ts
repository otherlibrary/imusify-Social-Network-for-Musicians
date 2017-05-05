import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';

@Component({
  selector: 'app-price-field',
  templateUrl: './price-field.component.html',
  styleUrls: ['./price-field.component.scss']
})
export class PriceFieldComponent implements OnInit {
  @Input() price: string;
  @Input() id: string;
  @Output()
  update: EventEmitter<any> = new EventEmitter<any>();


  public priceStatusCheck: boolean = false;
  public priceReady: boolean = false;

  constructor() { }

  ngOnInit() {
  }

  toggleCheck() {
    this.priceStatusCheck = !this.priceStatusCheck;
    this.priceReady = false;
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
