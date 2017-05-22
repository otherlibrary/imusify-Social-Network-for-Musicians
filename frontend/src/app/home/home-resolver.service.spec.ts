import { TestBed, inject } from '@angular/core/testing';

import { HomeResolverService } from './home-resolver.service';

describe('HomeResolverService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [HomeResolverService]
    });
  });

  it('should ...', inject([HomeResolverService], (service: HomeResolverService) => {
    expect(service).toBeTruthy();
  }));
});
