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
 * Library of interface functions and constants.
 *
 * @package     mod_foundry
 * @copyright   2024 GCZ Globals <mohiuddin@gcz.globals>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function setup()
{
    global $PAGE;
    $issuerid = get_config('foundry', 'issuerid');

    $issuer = \core\oauth2\api::get_issuer($issuerid);

    $wantsurl = $PAGE->url;
    $returnparams = ['wantsurl' => $wantsurl, 'sesskey' => sesskey(), 'id' => $issuerid];
    $returnurl = new moodle_url('/auth/oauth2/login.php', $returnparams);

    $client = \core\oauth2\api::get_user_oauth_client($issuer, $returnurl);

    if ($client) {
        if (!$client->is_logged_in()) {
            // TODO this doesnt actually work
            debugging('not logged in', DEBUG_DEVELOPER);
            redirect($client->get_login_url());
            //print_error('please re-authenticate your session');
        }
    }

    return $client;
}

function get_workspaces($client)
{
    if ($client == null) {
        debugging('error with client in get_workspaces', DEBUG_DEVELOPER);
        return;
    }

    // web request
    $url = get_config('foundry', 'topomojoapiurl') . '/workspaces';

    $response = $client->get($url);

    // echo 'client:</br></br></br><pre>' . print_r($client) . '</pre>';

    if (!$response) {
        debugging("no response received by get_workspaces for $url", DEBUG_DEVELOPER);
    }

    // echo "response:<br><pre>$response</pre>";

    if ($client->info['http_code'] !== 200) {
        debugging('response code ' . $client->info['http_code'] . " for $url", DEBUG_DEVELOPER);
        return;
    }
    $r = json_decode($response);

    if (!$r) {
        debugging('could not find item by id', DEBUG_DEVELOPER);
        return;
    }
    return $r;
}

function get_gamespaces($client)
{
    if ($client == null) {
        debugging('error with client in get_workspaces', DEBUG_DEVELOPER);
        return;
    }

    // web request
    $url = get_config('foundry', 'topomojoapiurl') . '/workspaces';

    $response = $client->get($url);

    // echo 'client:</br></br></br><pre>' . print_r($client) . '</pre>';

    if (!$response) {
        debugging("no response received by get_workspaces for $url", DEBUG_DEVELOPER);
    }

    // echo "response:<br><pre>$response</pre>";

    if ($client->info['http_code'] !== 200) {
        debugging('response code ' . $client->info['http_code'] . " for $url", DEBUG_DEVELOPER);
        return;
    }
    $r = json_decode($response);

    if (!$r) {
        debugging('could not find item by id', DEBUG_DEVELOPER);
        return;
    }
    return $r;
}

function get_gamesapce($client, $id)
{
    if ($client == null) {
        debugging('error with client in get_workspaces', DEBUG_DEVELOPER);
        return;
    }

    // web request
    $url = get_config('foundry', 'topomojoapiurl') . '/gamespace/' . $id;

    $response = $client->get($url);

    if (!$response) {
        debugging("no response received by get_workspaces for $url", DEBUG_DEVELOPER);
    }

    if ($client->info['http_code'] !== 200) {
        debugging('response code ' . $client->info['http_code'] . " for $url", DEBUG_DEVELOPER);
        return;
    }
    $r = json_decode($response);

    if (!$r) {
        debugging('could not find item by id', DEBUG_DEVELOPER);
        return;
    }
    return $r;
}

function start_gamespace($client, $settings)
{
    if ($client == null) {
        debugging('error with client in get_workspaces', DEBUG_DEVELOPER);
        return;
    }

    // web request
    $url = get_config('foundry', 'topomojoapiurl') . '/gamespace/';

    $client->setHeader('Content-Type: application/json');
    $response = $client->post($url, json_encode($settings));

    if (!$response) {
        debugging("no response received by get_workspaces for $url", DEBUG_DEVELOPER);
    }

    if ($client->info['http_code'] !== 200) {
        debugging('response code ' . $client->info['http_code'] . " for $url", DEBUG_DEVELOPER);
        return;
    }
    $r = json_decode($response);

    if (!$r) {
        debugging('could not find item by id', DEBUG_DEVELOPER);
        return;
    }
    return $r;
}

function stop_gamespace($client, $id)
{
    if ($client == null) {
        debugging('error with client in get_workspaces', DEBUG_DEVELOPER);
        return;
    }

    // web request
    $url = get_config('foundry', 'topomojoapiurl') . '/gamespace/' . $id . '/complete';

    $response = $client->post($url);

    if (!$response) {
        debugging("no response received by get_workspaces for $url", DEBUG_DEVELOPER);
    }

    if ($client->info['http_code'] !== 200) {
        debugging('response code ' . $client->info['http_code'] . " for $url", DEBUG_DEVELOPER);
        return;
    }
    $r = json_decode($response);

    if (!$r) {
        debugging('could not find item by id', DEBUG_DEVELOPER);
        return;
    }
    return $r;
}

function get_document($client, $id)
{
    if ($client == null) {
        debugging('error with client in get_workspaces', DEBUG_DEVELOPER);
        return;
    }

    // web request
    $url = get_config('foundry', 'topomojoapiurl') . '/document/' . $id;

    $response = $client->get($url);

    if (!$response) {
        debugging("no response received by get_workspaces for $url", DEBUG_DEVELOPER);
    }

    if ($client->info['http_code'] !== 200) {
        debugging('response code ' . $client->info['http_code'] . " for $url", DEBUG_DEVELOPER);
        return;
    }
    $r = json_decode($response);

    if (!$r) {
        debugging('could not find item by id', DEBUG_DEVELOPER);
        return;
    }
    return $r;
}
