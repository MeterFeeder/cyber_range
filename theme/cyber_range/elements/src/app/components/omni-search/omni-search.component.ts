import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IconComponent } from '../icon/icon.component';

@Component({
  selector: 'ekc-omni-search',
  standalone: true,
  imports: [CommonModule, IconComponent],
  templateUrl: './omni-search.component.html',
  styleUrl: './omni-search.component.scss',
})
export class OmniSearchComponent {}
