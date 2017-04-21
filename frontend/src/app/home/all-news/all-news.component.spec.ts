/* tslint:disable:no-unused-variable */
import {async, ComponentFixture, TestBed} from '@angular/core/testing';
import {By} from '@angular/platform-browser';
import {DebugElement} from '@angular/core';

import {AllNewsComponent} from './all-news.component';

describe('AllNewsComponent', () => {
    let component: AllNewsComponent;
    let fixture: ComponentFixture<AllNewsComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [AllNewsComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(AllNewsComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
