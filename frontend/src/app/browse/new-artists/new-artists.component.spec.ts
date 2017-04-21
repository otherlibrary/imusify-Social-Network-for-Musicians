import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {NewArtistsComponent} from './new-artists.component';

describe('NewArtistsComponent', () => {
    let component: NewArtistsComponent;
    let fixture: ComponentFixture<NewArtistsComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [NewArtistsComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(NewArtistsComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
