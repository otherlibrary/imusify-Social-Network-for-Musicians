import { Injectable } from '@angular/core';

@Injectable()
export class QueueService {
  public members: any[] = [];

  constructor() {}

  add(func) {
    if (func instanceof Function) {
      this.members.push(func);
    }
  }

  iterate(self) {
    if (this.members.length > 0) {
      const func = this.members.shift();
      func.call(self);
    }
  }

  clear() {
    this.members = [];
  }
}
