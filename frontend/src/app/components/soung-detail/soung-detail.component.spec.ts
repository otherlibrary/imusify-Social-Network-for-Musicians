import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {SoungDetailComponent} from './soung-detail.component';

describe('SoungDetailComponent', () => {
  let component: SoungDetailComponent;
  let fixture: ComponentFixture<SoungDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [SoungDetailComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SoungDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
