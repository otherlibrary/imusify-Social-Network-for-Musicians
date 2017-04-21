import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {StaticTermsComponent} from './static-terms.component';

describe('StaticTermsComponent', () => {
    let component: StaticTermsComponent;
    let fixture: ComponentFixture<StaticTermsComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [StaticTermsComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(StaticTermsComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
