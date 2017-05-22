import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {ITracksData} from "../../interfases/ITracksData";

@Component({
    selector: 'app-articles',
    templateUrl: './articles.component.html',
    styleUrls: ['./articles.component.scss']
})
export class ArticlesComponent implements OnInit {
    public articles: any[];
    public homeData: ITracksData;

    constructor(private _route: ActivatedRoute) {
    }

    ngOnInit() {
        this._route.parent.data.subscribe(
          (data: { homeData: ITracksData }) => {
              this.homeData = data.homeData;
              this.articles = this.homeData.records.filter((article: any) => {
                  return article.is_article;
              });
          }
        );
    }

}
