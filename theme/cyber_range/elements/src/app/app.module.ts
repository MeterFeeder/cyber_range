import { Injector, NgModule } from "@angular/core";
import { createCustomElement } from "@angular/elements";
import { IconComponent } from "./components/icon/icon.component";
import { BrowserModule } from "@angular/platform-browser";
import { OmniSearchComponent } from "./components/omni-search/omni-search.component";
import { CourseOverviewComponent } from "./components/course-overview/course-overview.component";

@NgModule({
  declarations: [],
  imports: [BrowserModule],
  providers: [],
  bootstrap: []
})
export class AppModule {
  constructor(private injector: Injector) {}
  components = [
    { selector: "ekc-course-overview", class: CourseOverviewComponent},
    { selector: "ekc-icon", class: IconComponent},
    { selector: "ekc-omni-search", class: OmniSearchComponent},
  ];

  ngDoBootstrap() {
    for (const component of this.components) {
      const element = createCustomElement(component.class, {
        injector: this.injector,
      });
      customElements.define(component.selector, element);
    }
  }

}
