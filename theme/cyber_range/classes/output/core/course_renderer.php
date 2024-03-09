<?php

namespace theme_cyber_range\output\core;

global $CFG;
require_once($CFG->dirroot . '/course/renderer.php');

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

        /* -- Thi swill render the search bar for categories:
         $actionbar = new \core_course\output\category_action_bar($this->page, $coursecat);
         $output = $this->render_from_template('core_course/category_actionbar', $actionbar->export_for_template($this));
        */
        $output = '<p>Choose from over (x-number) of categories.</p><section class="course-category">Categories go Here</section>';
        $output .= parent::course_category($category);
        return $output;
        
    }

    public function coursecat_coursebox(\coursecat_helper $chelper, $course, $additionalclasses = '')
    {
        global $CFG;
        $output = "<H3>course detail display</H3>";
        $output.= parent::coursecat_coursebox($chelper, $course, $additionalclasses);
       
        return $output;
    }
}