import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PriceFieldComponent } from './price-field.component';

describe('PriceFieldComponent', () => {
  let component: PriceFieldComponent;
  let fixture: ComponentFixture<PriceFieldComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PriceFieldComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PriceFieldComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
