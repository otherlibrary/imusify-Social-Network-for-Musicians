import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {StaticPrivacyComponent} from './static-privacy.component';

describe('StaticPrivacyComponent', () => {
    let component: StaticPrivacyComponent;
    let fixture: ComponentFixture<StaticPrivacyComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [StaticPrivacyComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(StaticPrivacyComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
