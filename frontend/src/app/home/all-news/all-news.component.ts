import {Component, OnInit} from '@angular/core';
import {HomeService} from "../home.service";
import {EmitterService} from "../../shared/services/emitter.service";
import {SharedService} from "../../shared/shared.service";

@Component({
  selector: 'app-all-news',
  templateUrl: './all-news.component.html',
  styleUrls: ['./all-news.component.css']
})
export class AllNewsComponent implements OnInit {
  homeData: any;
  records: any;
  sharedUrl: null;

  constructor(
    private _homeService: HomeService
  ) {}

  ngOnInit() {
    this.getAllNews();
  }

  getAllNews() {
    EmitterService.get('TOGGLE_PRELOADER').emit(true);
    this._homeService.getAllNews().subscribe(data => {
      this.homeData = data;
      this.records = data.records;

      EmitterService.get('TOGGLE_PRELOADER').emit(false);
    });
  }
}
