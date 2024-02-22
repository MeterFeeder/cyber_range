<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * profile_snapshot block caps.
 *
 * @package    block_profile_snapshot
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_profile_snapshot extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_profile_snapshot');
        
    }

    function get_content() {
        global $DB, $CFG, $OUTPUT, $USER;
        $id = optional_param('id', $USER->id, PARAM_INT);    // User id; -1 if creating new user.
        $systemcontext = context_system::instance();
        require_capability('moodle/user:update', $systemcontext);
        $user = $DB->get_record('user', array('id' => $id), '*', MUST_EXIST);

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $context = [
            'user' => $user,
            'learning_hours' => .03,
            'courses_completed' => 0,
            'labs_completed' => 0,
            'assessments_completed' => 0,
            'daily_streak' => 0,
        ];
        // echo '<pre>'; print_r($context); echo '</pre>';
        $this->content->text = $OUTPUT->render_from_template('block_profile_snapshot/main', $context);
        
        return (object) $this->content;
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('all' => false,
                     'site' => false,
                     'site-index' => false,
                     'course-view' => false, 
                     'course-view-social' => false,
                     'mod' => false, 
                     'mod-quiz' => false,
                     'my' => true);
    }

    public function instance_allow_multiple() {
          return true;
    }

    function has_config() {return true;}

    public function cron() {
            mtrace( "Hey, my cron script is running" );
             
                 // do something
                  
                      return true;
    }
}
