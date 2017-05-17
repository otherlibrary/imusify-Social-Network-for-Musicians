import { TestBed, async, inject } from '@angular/core/testing';

import { AuthAllSuccessGuard } from './auth-all-success.guard';

describe('AuthAllSuccessGuard', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [AuthAllSuccessGuard]
    });
  });

  it('should ...', inject([AuthAllSuccessGuard], (guard: AuthAllSuccessGuard) => {
    expect(guard).toBeTruthy();
  }));
});
