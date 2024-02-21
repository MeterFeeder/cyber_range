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
 * Plugin version and other meta-data are defined here.
 *
 * @package     mod_foundry
 * @copyright   2024 GCZ Globals <mohiuddin@gcz.globals>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {
    //--- general settings -----------------------------------------------------------------------------------

    $options = ['Display Link to Player', 'Embed VM App'];
    $settings->add(new admin_setting_configselect(
        'foundry/vmapp',
        get_string('vmapp', 'foundry'),
        get_string('configvmapp', 'foundry'),
        1,
        $options
    ));

    $options = ['Dropdown', 'Searchable'];
    $settings->add(new admin_setting_configselect(
        'foundry/autocomplete',
        get_string('autocomplete', 'foundry'),
        get_string('configautocomplete', 'foundry'),
        1,
        $options
    ));

    $options = [];
    $issuers = core\oauth2\api::get_all_issuers();
    foreach ($issuers as $issuer) {
        $options[$issuer->get('id')] = s($issuer->get('name'));
    }
    $settings->add(new admin_setting_configselect(
        'foundry/issuerid',
        get_string('issuerid', 'foundry'),
        get_string('configissuerid', 'foundry'),
        0,
        $options
    ));

    $settings->add(new admin_setting_configtext(
        'foundry/topomojoapiurl',
        get_string('topomojoapiurl', 'foundry'),
        get_string('configtopomojoapiurl', 'foundry'),
        '',
        PARAM_URL,
        60
    ));
}
