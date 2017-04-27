import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BrowseTrackComponent } from './browse-track.component';

describe('BrowseTrackComponent', () => {
  let component: BrowseTrackComponent;
  let fixture: ComponentFixture<BrowseTrackComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BrowseTrackComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BrowseTrackComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
