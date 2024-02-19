import { Component, HostBinding, Input } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'ekc-icon',
  standalone: true,
  imports: [CommonModule],
  template: ``,
  styleUrls: ['./icon.component.scss'],
})
export class IconComponent {
  @HostBinding('style.-webkit-mask-image')
  private _path!: string;

  @Input()
  public set type(filePath: string) {
    this._path = `var(--icon-${filePath})`;
  }

}
