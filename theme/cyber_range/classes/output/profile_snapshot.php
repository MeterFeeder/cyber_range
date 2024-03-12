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
namespace theme\cyber_range\classes\output;

defined('MOODLE_INTERNAL') || die();

class profile_snapshot
{

    public $title;
    public $content;
    public $template = 'theme_cyber_range/partials/profile_snapshot';

    public function __construct()
    {
        $this->title = get_string('profile_snapshot', 'theme_cyber_range');
        $this->content = new \stdClass();
    }

    public function html()
    {
        global $OUTPUT;
        $content = $this->get_content();
        return $OUTPUT->render_from_template($this->template, $content);
    }

    public function get_content()
    {
        global $DB, $USER, $PAGE;

        $id = optional_param('id', $USER->id, PARAM_INT);    // User id; -1 if creating new user.

        // If a user is trying to edit someone else, require capability.
        if ($id != $USER->id)
        {
            $systemcontext = \context_system::instance();
            require_capability('moodle/user:update', $systemcontext);
        }
        
        $user = $DB->get_record('user', array('id' => $id), '*', MUST_EXIST);
        $info = user_get_user_navigation_info($user, $PAGE);

        $this->content->user = $user;
        $this->content->items = [
            'learning_hours' => .03,
            'courses_completed' => 0,
            'labs_completed' => 0,
            'assessments_completed' => 0,
            'daily_streak' => 0,
        ];
        $this->content->avatardata = [
            'content' => $info->metadata['useravatar'],
            'classes' => 'current'
        ];
        $this->content->userfullname = $info->metadata['realuserfullname'] ?? $info->metadata['userfullname'];

        $this->content->items = [
            [
                'name' => get_string('learning_hours', 'theme_cyber_range'), 
                'value' => '0.03', 
                'icon' => 'book', 
                'class' => 'primary'
            ],
            [
                'name' => get_string('courses_completed', 'theme_cyber_range'),
                'value' => '0',
                'icon' => 'bookmark',
                'class' => 'success'
            ],
            [
                'name' =>  get_string('labs_completed', 'theme_cyber_range'), 
                'value' => '0',
                'icon' => 'glass',
                'class' => 'secondary'
            ],
            [
                'name' =>  get_string('assessments_completed', 'theme_cyber_range'),
                'value' => '0',
                'icon' => 'book',
                'class' => 'primary'
            ],
            [
                'name' =>  get_string('daily_streak', 'theme_cyber_range'),
                'value' => '0',
                'icon' => 'hand-thumbs-up',
                'class' => 'danger'
            ]
        ];
       
        return $this->content;
    }

}
