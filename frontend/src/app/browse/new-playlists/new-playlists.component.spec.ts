import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {NewPlaylistsComponent} from './new-playlists.component';

describe('NewPlaylistsComponent', () => {
    let component: NewPlaylistsComponent;
    let fixture: ComponentFixture<NewPlaylistsComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [NewPlaylistsComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(NewPlaylistsComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
