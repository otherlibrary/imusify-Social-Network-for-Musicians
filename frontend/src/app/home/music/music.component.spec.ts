/* tslint:disable:no-unused-variable */
import {async, ComponentFixture, TestBed} from '@angular/core/testing';
import {By} from '@angular/platform-browser';
import {DebugElement} from '@angular/core';

import {MusicComponent} from './music.component';

describe('MusicComponent', () => {
    let component: MusicComponent;
    let fixture: ComponentFixture<MusicComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [MusicComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(MusicComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
