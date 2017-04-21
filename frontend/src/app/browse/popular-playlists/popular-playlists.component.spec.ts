import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {PopularPlaylistsComponent} from './popular-playlists.component';

describe('PopularPlaylistsComponent', () => {
    let component: PopularPlaylistsComponent;
    let fixture: ComponentFixture<PopularPlaylistsComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [PopularPlaylistsComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(PopularPlaylistsComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
