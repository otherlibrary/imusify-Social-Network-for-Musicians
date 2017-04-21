import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {PopularTracksComponent} from './popular-tracks.component';

describe('PopularTracksComponent', () => {
    let component: PopularTracksComponent;
    let fixture: ComponentFixture<PopularTracksComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [PopularTracksComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(PopularTracksComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
