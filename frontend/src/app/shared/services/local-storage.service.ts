import { Injectable } from '@angular/core';
import {ILocalStorgeItem} from "../../interfases/ILocalStorageItem";
import {Subject} from "rxjs/Subject";

@Injectable()
export class LocalStorageService {
  public localStorageSubject: Subject<Object> = new Subject<Object>();

  constructor() {}

  getLocalStorage(key) {
    return localStorage.getItem(key);
  }

  setLocalStorage(obj: ILocalStorgeItem): void {
    localStorage.setItem(obj.key, obj.val);
    this.localStorageSubject.next('save');
  }

  removeLocalStorage(key): void {
    localStorage.removeItem(key);
    this.localStorageSubject.next('remove');
  }

  clearLocalStorage() {
    localStorage.clear();
    this.localStorageSubject.next('clear');
  }
}
