import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {StaticVideoComponent} from './static-video.component';

describe('StaticVideoComponent', () => {
    let component: StaticVideoComponent;
    let fixture: ComponentFixture<StaticVideoComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [StaticVideoComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(StaticVideoComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
