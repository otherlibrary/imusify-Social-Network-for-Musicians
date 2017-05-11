import {Component, Input, OnInit} from '@angular/core';
import {IArticle} from "../../interfases";

@Component({
  selector: 'app-home-article',
  templateUrl: './home-article.component.html',
  styleUrls: ['./home-article.component.scss']
})
export class HomeArticleComponent implements OnInit {
  @Input() article: IArticle;

  constructor() { }

  ngOnInit() {
  }

}
