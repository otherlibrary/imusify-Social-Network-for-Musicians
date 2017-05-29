import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PlayerBigComponent } from './player-slider.component';

describe('PlayerBigComponent', () => {
  let component: PlayerBigComponent;
  let fixture: ComponentFixture<PlayerBigComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PlayerBigComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PlayerBigComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
