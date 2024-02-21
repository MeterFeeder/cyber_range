import { Injector, NgModule } from "@angular/core";
import { createCustomElement } from "@angular/elements";
import { IconComponent } from "./components/icon/icon.component";
import { BrowserModule } from "@angular/platform-browser";
import { OmniSearchComponent } from "./components/omni-search/omni-search.component";
import { MycoursesComponent } from "./components/mycourses/mycourses.component";
import { IncourseComponent } from "./components/incourse/incourse.component";

@NgModule({
  declarations: [],
  imports: [BrowserModule],
  providers: [],
  bootstrap: []
})
export class AppModule {
  constructor(private injector: Injector) {}
  components = [
    { selector: "ekc-icon", class: IconComponent},
    { selector: "ekc-omni-search", class: OmniSearchComponent},
    { selector: "ekc-mycourses", class: MycoursesComponent},
    { selector: "ekc-incourse", class: IncourseComponent}
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
