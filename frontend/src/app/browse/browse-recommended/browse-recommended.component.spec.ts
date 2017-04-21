import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {BrowseRecommendedComponent} from './browse-recommended.component';

describe('BrowseRecommendedComponent', () => {
    let component: BrowseRecommendedComponent;
    let fixture: ComponentFixture<BrowseRecommendedComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [BrowseRecommendedComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(BrowseRecommendedComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
