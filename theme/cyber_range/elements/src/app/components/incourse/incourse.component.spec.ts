import { ComponentFixture, TestBed } from '@angular/core/testing';

import { IncourseComponent } from './incourse.component';

describe('IncourseComponent', () => {
  let component: IncourseComponent;
  let fixture: ComponentFixture<IncourseComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [IncourseComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(IncourseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
