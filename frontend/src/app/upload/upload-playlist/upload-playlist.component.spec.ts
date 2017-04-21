import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {UploadPlaylistComponent} from './upload-playlist.component';

describe('UploadPlaylistComponent', () => {
    let component: UploadPlaylistComponent;
    let fixture: ComponentFixture<UploadPlaylistComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [UploadPlaylistComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(UploadPlaylistComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
