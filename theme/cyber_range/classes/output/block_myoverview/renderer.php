<?php

namespace theme_cyber_range\output\block_myoverview;

use block_myoverview\output\main;
use plugin_renderer_base;

class renderer extends plugin_renderer_base {
    public function render_main(main $main) {
       echo 'Hello, World!';
       var_dump($main->export_for_template($this));
    }
}