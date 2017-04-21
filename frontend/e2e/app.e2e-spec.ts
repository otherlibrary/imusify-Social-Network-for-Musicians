import { ImusifyNewPage } from './app.po';

describe('imusify-new App', () => {
  let page: ImusifyNewPage;

  beforeEach(() => {
    page = new ImusifyNewPage();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
