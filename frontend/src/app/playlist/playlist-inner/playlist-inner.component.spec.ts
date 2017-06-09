import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PlaylistInnerComponent } from './playlist-inner.component';

describe('PlaylistInnerComponent', () => {
  let component: PlaylistInnerComponent;
  let fixture: ComponentFixture<PlaylistInnerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PlaylistInnerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PlaylistInnerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
