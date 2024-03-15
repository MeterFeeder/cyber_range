<?php

namespace theme_cyber_range\output\core;

global $CFG;
require_once($CFG->dirroot . '/course/renderer.php');


use core_course_category;
use moodle_url;
use lang_string;
use coursecat_helper;

class course_renderer extends \core_course_renderer
{

    public function render_course_category($course_category)
    {
        global $OUTPUT;
        echo "TODO: generated in \course_renderer::render_course_category()";
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

        global $CFG;
        // return '<p>This is one category <strong>Figma Screen 30</strong></p>';
        $templatecontext = [
            'name' => $category->name,
        ];

        // Print current category description
        $chelper = new coursecat_helper();
        if ($description = $chelper->get_category_formatted_description($category)) {
            $templatecontext['description'] = $this->box($description, array('class' => 'generalbox info'));
        }

        // Prepare parameters for courses and categories lists in the tree
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_AUTO)
            ->set_attributes(array('class' => 'row category-browse category-browse-' . $category->id));

        $coursedisplayoptions = array();
        $catdisplayoptions = array();
        $browse = optional_param('browse', null, PARAM_ALPHA);
        $perpage = optional_param('perpage', $CFG->coursesperpage, PARAM_INT);
        $page = optional_param('page', 0, PARAM_INT);
        $baseurl = new moodle_url('/course/index.php');
        if ($category->id) {
            $baseurl->param('categoryid', $category->id);
        }
        if ($perpage != $CFG->coursesperpage) {
            $baseurl->param('perpage', $perpage);
        }
        $coursedisplayoptions['limit'] = $perpage;
        $catdisplayoptions['limit'] = $perpage;
        if ($browse === 'courses' || !$category->get_children_count()) {
            $coursedisplayoptions['offset'] = $page * $perpage;
            $coursedisplayoptions['paginationurl'] = new moodle_url($baseurl, array('browse' => 'courses'));
            $catdisplayoptions['nodisplay'] = true;
            $catdisplayoptions['viewmoreurl'] = new moodle_url($baseurl, array('browse' => 'categories'));
            $catdisplayoptions['viewmoretext'] = new lang_string('viewallsubcategories');
        } else if ($browse === 'categories' || !$category->get_courses_count()) {
            $coursedisplayoptions['nodisplay'] = true;
            $catdisplayoptions['offset'] = $page * $perpage;
            $catdisplayoptions['paginationurl'] = new moodle_url($baseurl, array('browse' => 'categories'));
            $coursedisplayoptions['viewmoreurl'] = new moodle_url($baseurl, array('browse' => 'courses'));
            $coursedisplayoptions['viewmoretext'] = new lang_string('viewallcourses');
        } else {
            // we have a category that has both subcategories and courses, display pagination separately
            $coursedisplayoptions['viewmoreurl'] = new moodle_url($baseurl, array('browse' => 'courses', 'page' => 1));
            $catdisplayoptions['viewmoreurl'] = new moodle_url($baseurl, array('browse' => 'categories', 'page' => 1));
        }
        $chelper->set_courses_display_options($coursedisplayoptions)->set_categories_display_options($catdisplayoptions);
  
        // Display course category tree.
        $templatecontext = [];
        $templatecontext['category_tree'] = $this->coursecat_tree($chelper, $category);


        $output = $this->render_from_template('theme_cyber_range/single_category_content', $templatecontext);
        return $output;
    }


    private function categories_search_content($categories)
    {
        global $OUTPUT;
        // $OUTPUT->body_attributes('categories');
        $templatecontext = [];

        // Show the action bar
        $actionbar = new \core_course\output\category_action_bar($this->page, $categories);
        $templatecontext['actionbar'] = $this->render_from_template('core_course/category_actionbar', $actionbar->export_for_template($this));
        $templatecontext['categories'] = $this->get_category_list($this);


        $templatecontext['unlock_image'] = $OUTPUT->image_url('unlock', 'theme');
        $output = $this->render_from_template('theme_cyber_range/course_category', $templatecontext);

        return $output;

    }

    /**
     * Gets the url_select to be displayed in the participants page if available.
     *
     * @param \renderer_base $output
     * @return object|null The content required to render the url_select
     */
    protected function get_category_list(\renderer_base $output): array {
        $list = [];
        if (!core_course_category::is_simple_site()) {
            $categories = core_course_category::make_categories_list();
            if (count($categories) > 1) {
                foreach ($categories as $id => $cat) {
                    $list[$id] = core_course_category::get($id);
                    // $url = new moodle_url($this->page->url, ['categoryid' => $id]);
                    // $options[$url->out()] = $cat;
                }
                // $currenturl = new moodle_url($this->page->url, ['categoryid' => $this->category->id]);
                // $select = new \url_select($options, $currenturl, null);
                // $select->set_label(get_string('categories'), ['class' => 'sr-only']);
                // $select->class .= ' text-truncate w-100';
                // return $select->export_for_template($output);
            }
        }

        return $list;
    }

    protected function coursecat_coursebox(\coursecat_helper $chelper, $course, $additionalclasses = '')
    {
        global $PAGE;
        
        // Don't use out custom course box for the site index page.
        if ($PAGE->pagetype != 'course-index-category') {
            return parent::coursecat_coursebox($chelper, $course, $additionalclasses);
        }


        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');
        }
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof stdClass) {
            $course = new \core_course_list_element($course);
        }

        $classes = trim('coursebox clearfix '. $additionalclasses);
        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $classes .= ' collapsed';
        }

        $context = [
            'name' => $chelper->get_course_formatted_name($course),
            'description' => $this->coursecat_coursebox_content($chelper, $course),
            'link' => new moodle_url('/course/view.php', ['id' => $course->id]),
        ];
       
        
        return $this->render_from_template('theme_cyber_range/partials/category_course_box', $context);
    }

     /**
     * Returns HTML to display a tree of subcategories and courses in the given category
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_category $coursecat top category (this category's name and description will NOT be added to the tree)
     * @return string
     */
    protected function coursecat_tree(coursecat_helper $chelper, $coursecat) {
        // Reset the category expanded flag for this course category tree first.
        $this->categoryexpandedonload = false;
        return $this->coursecat_category_content($chelper, $coursecat, 0);
    }
    

}

