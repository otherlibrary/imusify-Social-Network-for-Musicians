import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {StaticLegalComponent} from './static-legal.component';

describe('StaticLegalComponent', () => {
    let component: StaticLegalComponent;
    let fixture: ComponentFixture<StaticLegalComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [StaticLegalComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(StaticLegalComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
