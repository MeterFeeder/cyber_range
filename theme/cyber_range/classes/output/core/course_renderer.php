<?php

namespace theme_cyber_range\output\core;

global $CFG;
require_once($CFG->dirroot . '/course/renderer.php');


use core_course_category;

class course_renderer extends \core_course_renderer
{

    public function render_course_category($course_category)
    {
        global $OUTPUT;
        echo "hello, world!";
    }

    // /** invoked from /course/index.php */
    public function course_category($category)
    {
        global $OUTPUT;
        $templatecontext = [];
        
        // Get the categories to idsplay
        $usertop = core_course_category::user_top();
        if (empty($category)) {
            return $this->categories_search_content($usertop);
        } else if (is_object($category) && $category instanceof core_course_category) {
            return $this->single_category_content($category);
        } else {
            $category = core_course_category::get(is_object($category) ? $category->id : $category);
            return $this->single_category_content($category);
        }
        
       
    }

    private function single_category_content($category)
    {
        return '<p>This is one category <strong>Figma Screen 30</strong></p>';
    }

    private function categories_search_content($categories)
    {
        global $OUTPUT;
        $templatecontext = [];

        // Show the action bar
        $actionbar = new \core_course\output\category_action_bar($this->page, $categories);
        $templatecontext['actionbar'] = $this->render_from_template('core_course/category_actionbar', $actionbar->export_for_template($this));


        $templatecontext['unlock_image'] = $OUTPUT->image_url('unlock', 'theme');
        $output = $this->render_from_template('theme_cyber_range/course_category', $templatecontext);

        return $output;

    }

    public function coursecat_coursebox(\coursecat_helper $chelper, $course, $additionalclasses = '')
    {
        global $CFG;
        $output = "<H3>course detail display</H3>";
        $output .= parent::coursecat_coursebox($chelper, $course, $additionalclasses);

        return $output;
    }
}