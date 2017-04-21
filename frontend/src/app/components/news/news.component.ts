import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";

@Component({
  selector: 'app-news',
  templateUrl: 'news.component.html',
  styleUrls: ['news.component.scss']
})
export class NewsComponent implements OnInit {

  constructor(private _router: Router) {
  }

  ngOnInit() {
    console.log('news init');
  }

  closePopup() {
    this._router.navigate([{outlets: {popup: null}}]);
  }
}
