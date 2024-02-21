<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * The main mod_foundry configuration form.
 *
 * @package     mod_foundry
 * @copyright   2024 GCZ Globals <mohiuddin@gcz.globals>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->dirroot . '/course/moodleform_mod.php';
require_once $CFG->dirroot . '/mod/foundry/locallib.php';

/**
 * Module instance settings form.
 *
 * @package     mod_foundry
 * @copyright   2024 GCZ Globals <mohiuddin@gcz.globals>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_foundry_mod_form extends moodleform_mod
{
    /**
     * Defines forms elements
     */
    public function definition()
    {
        global $COURSE, $CFG, $DB, $PAGE;
        $mform = $this->_form;

        $config = get_config('foundry');

        //-------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // $this->standard_intro_elements();

        // pull workspaces from topomojo
        $systemauth = setup();
        $this->workspaces = get_workspaces($systemauth);

        $workspaces = [];

        foreach ($this->workspaces as $workspace) {
            $workspaces[$workspace->id] = s($workspace->name);
        }

        array_unshift($workspaces, '');
        asort($workspaces);

        $options = [
            'multiple' => false,
            'placeholder' => get_string('selectname', 'mod_foundry')
        ];

        if ($config->autocomplete) {
            $mform->addElement('autocomplete', 'workspaceid', get_string('workspaces', 'foundry'), $workspaces, $options);
        } else {
            $mform->addElement('select', 'workspaceid', get_string('workspaces', 'foundry'), $workspaces);
        }

        $mform->addRule('workspaceid', null, 'required', null, 'client');
        $mform->addRule('workspaceid', 'You must choose an option', 'minlength', '2');
        $mform->setDefault('workspaceid', null);
        $mform->addHelpButton('workspaceid', 'workspaces', 'foundry');

        //-------------------------------------------------------
        $mform->addElement('header', 'optionssection', get_string('appearance'));

        $options = ['Display Link to Player', 'Embed VM App'];
        $mform->addElement('select', 'vmapp', get_string('vmapp', 'foundry'), $options);
        $mform->setDefault('vmapp', $config->vmapp);
        $mform->addHelpButton('vmapp', 'vmapp', 'foundry');

        $options = ['', 'Countdown', 'Timer'];
        $mform->addElement('select', 'clock', get_string('clock', 'foundry'), $options);
        $mform->setDefault('clock', '');
        $mform->addHelpButton('clock', 'clock', 'foundry');

        //-------------------------------------------------------
        $this->standard_coursemodule_elements();

        //-------------------------------------------------------
        $this->add_action_buttons();
    }

    public function data_postprocessing($data)
    {
        if (!$data->workspaceid) {
            echo 'return to settings page<br>';
            exit;
        }
        // if (!$data->vmapp) {
        //     $data->vmapp = 0;
        // }
        $index = array_search($data->workspaceid, array_column($this->workspaces, 'id'), true);

        echo '<pre>';
        var_dump($data);
        echo '</pre>';

        $data->gamespaceid = '';
        $data->name = $this->workspaces[$index]->name;
        $data->intro = $this->workspaces[$index]->description;
        $data->introeditor['itemid'] = 0;
        $data->introeditor['text'] = '';
        $data->introeditor['format'] = FORMAT_PLAIN;
    }
}
