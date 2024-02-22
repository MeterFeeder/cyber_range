import { Component } from '@angular/core';
import { IconComponent } from '../icon/icon.component';

@Component({
  selector: 'ekc-course-overview',
  standalone: true,
  imports: [IconComponent],
  templateUrl: './course-overview.component.html',
  styleUrl: './course-overview.component.scss'
})
export class CourseOverviewComponent {
  public tempModules = [
    {
      "title": "1 - Test title",
      "description": "Test description",
      "number": 1
    },
    {
      "title": "2 - Test title",
      "description": "Test description",
      "number": 2
    },
    {
      "title": "3 - Test title",
      "description": "Test description",
      "number": 3
    },
    {
      "title": "4 - Test title",
      "description": "Test description",
      "number": 4
    },
    {
      "title": "5 - Test title",
      "description": "Test description",
      "number": 5
    },
  ]

  public advance() {
    console.log('i can get here?')
    // let copy = [...this.tempModules];
    // let first = copy.shift()
    // if (first) {
    //   copy.push(first);
    //   console.log(copy);
    //   this.tempModules = copy;
    // }
  }

}


