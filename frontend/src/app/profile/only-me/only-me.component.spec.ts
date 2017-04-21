import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {OnlyMeComponent} from './only-me.component';

describe('OnlyMeComponent', () => {
    let component: OnlyMeComponent;
    let fixture: ComponentFixture<OnlyMeComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [OnlyMeComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(OnlyMeComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
