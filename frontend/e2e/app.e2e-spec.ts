import { Imusify4Page } from './app.po';

describe('imusify4 App', () => {
  let page: Imusify4Page;

  beforeEach(() => {
    page = new Imusify4Page();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
