/* tslint:disable:no-unused-variable */
import {async, ComponentFixture, TestBed} from '@angular/core/testing';
import {By} from '@angular/platform-browser';
import {DebugElement} from '@angular/core';

import {BrowseComponent} from './browse.component';

describe('BrowseComponent', () => {
    let component: BrowseComponent;
    let fixture: ComponentFixture<BrowseComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [BrowseComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(BrowseComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
