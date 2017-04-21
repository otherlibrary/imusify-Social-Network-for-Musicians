import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {BrowseExploreComponent} from './browse-explore.component';

describe('BrowseExploreComponent', () => {
    let component: BrowseExploreComponent;
    let fixture: ComponentFixture<BrowseExploreComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [BrowseExploreComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(BrowseExploreComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
