import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";
import {Observable} from "rxjs";

@Component({
  selector: 'app-messages',
  templateUrl: './messages.component.html',
  styleUrls: ['./messages.component.scss']
})
export class MessagesComponent implements OnInit {
  public isShow: boolean = false;

  constructor(private _router: Router) {
  }

  ngOnInit() {
    this.getMessages();
  }

  getMessages() {
    this.server().subscribe(() => {
      this.isShow = true;
    })
  }

  server() {
    return new Observable(observable => {
      setTimeout(function () {
        observable.next(123)
      }, 500);
    })
  }

  closeMessages() {
    this.isShow = false;
    this._router.navigate([{outlets: {popup: null}}]);
  }

}
