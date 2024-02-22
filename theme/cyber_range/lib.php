<?php

// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.

// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();



function fix_path($node, $attr)
{
    // global $CFG;
    $path = $node->getAttribute($attr);
    if (strpos($path, 'http') === 0) {
        return;
    }
    $node->setAttribute($attr, '/theme/cyber_range/elements/dist/elements/browser/' . $path);
    return $node;
}
function theme_cyber_range_get_angular_content($templatecontext)
{

    if (array_key_exists('primarymoremenu', $templatecontext) && array_key_exists('nodearray', $templatecontext['primarymoremenu'])) {
        $templatecontext["primarymoremenu"]['nodearray'] = theme_cyber_range_add_menu_icons($templatecontext["primarymoremenu"]['nodearray']);
    }

    $baseurl = '/elements/dist/elements/browser/';

    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $index = __DIR__ . "$baseurl/index.html";
    $html = file_get_contents($index);
    $doc->loadHTML($html);

    $xpath = new DOMXpath($doc);
    $nodes = $xpath->query('//body/script[@src]');
    $javascript = '';
    foreach ($nodes as $node) {
        $javascript .= $doc->saveHTML(fix_path($node, 'src'));
    }

    $nodes = $xpath->query('//head/link[@rel="stylesheet"]');
    $styles = '';
    foreach ($nodes as $node) {
        $styles .= $doc->saveHTML(fix_path($node, 'href'));
    }

    // var_dump($javascript, $styles);
    
    $templatecontext['javascript'] = $javascript;
    $templatecontext['styles'] = $styles;
    $templatecontext['baseurl'] = "/";
    // $templatecontext['baseurl'] = "/theme/cyber_range$baseurl";
    
    $templatecontext['logo_image_url'] = $templatecontext['output']->image_url('heartland-cr-logo', 'theme');
    return $templatecontext;
    // $templatecontext->css;
}

function theme_cyber_range_add_menu_icons($items) {

    if (!count($items)) {
        return $items;
    }

    for ($i = 0; $i < count($items); $i++) {
        switch ($items[$i]['key']) {
            case 'home':
                $items[$i]['ekc'] = 'home-circlebkgd';
                break;
            case 'siteadminnode':
                $items[$i]['ekc'] = 'gear';
                break;
            case 'courses':
                $items[$i]['ekc'] = 'book-circlebkgd';
                break;
            case 'mycourses':
                $items[$i]['ekc'] = 'book-circlebkgd';
                break;
            case 'myhome':
                $items[$i]['ekc'] = 'profile-circlebkgd';
                break;
            case 'dashboard':
                $items[$i]['ekc'] = 'profile-circlebkgd';
                break;
            case 'calendar':
                $items[$i]['ekc'] = 'stopwatch-circlebkgd';
                break;
            case 'grades':
                $items[$i]['ekc'] = 'icon-grades';
                break;
            case 'messages':
                $items[$i]['ekc'] = 'headset-circlebkgd';
                break;
            case 'notifications':
                $items[$i]['ekc'] = 'headset-circlebkgd';
                break;
            case 'badges':
                $items[$i]['ekc'] = 'trophy-circlebkgd';
                break;
            case 'competencies':
                $items[$i]['ekc'] = 'trophy-circlebkgd';
                break;
            case 'analytics':
                $items[$i]['ekc'] = 'icon-analytics';
                break;
            case 'reports':
                $items[$i]['ekc'] = 'icon-reports';
                break;
            case 'plugins':
                $items[$i]['ekc'] = 'icon-plugins';
                break;
            case 'admin':
                $items[$i]['ekc'] = 'icon-admin';
                break;
            case 'user':
                $items[$i]['ekc'] = 'icon-user';
                break;
            case 'lang':
                $items[$i]['ekc'] = 'icon-lang';
                break;
            case 'more':
                $items[$i]['ekc'] = 'icon-more';
                break;
            case 'add':
                $items[$i]['ekc'] = 'icon-add';
                break;
            case 'edit':
                $items[$i]['ekc'] = 'icon-edit';
                break;
            case 'delete':
                $items[$i]['ekc'] = 'icon-delete';
                break;
            case 'settings':
                $items[$i]['ekc'] = 'icon-settings';
                break;
            default:
                $items[$i]['ekc'] = 'icon-default';
                break;
        }
    }
    return $items;
}