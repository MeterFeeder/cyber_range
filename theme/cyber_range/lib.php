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

function make_nav_link($key, $title, $url, $icon = '')
{
    global $PAGE;
    $active = $PAGE->url->compare($url, URL_MATCH_BASE);
    return [
        'key' => $key,
        'title' => $title,
        'url' => $url,
        'icon' => $icon,
        'isactive' => $active,
        'hasChildren' => false,
    ];
}
function merge_link($array, $new, $position = -1)
{
    return array_merge(array_slice($array, 0, $position), [$new], array_slice($array, $position));
}
function theme_cyber_range_get_angular_content($templatecontext)
{

    if (array_key_exists('primarymoremenu', $templatecontext) && array_key_exists('nodearray', $templatecontext['primarymoremenu']))
    {
        // Add the course categories link to the primary more menu.
        $link = make_nav_link('categories', 'Categories', new moodle_url('/course/index.php'));
        $templatecontext["primarymoremenu"]['nodearray'] = merge_link($templatecontext["primarymoremenu"]['nodearray'], $link, -1);
        

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


/**
 *
 * Get the current page to allow us to check if the block is allowed to display.
 *
 * @return string The page name, which is either "frontpage", "dashboard", "coursepage", "coursesectionpage" or empty string.
 *
 */
function theme_cyber_range_get_current_page() {
    global $PAGE;

    // This will store the kind of activity page type we find. E.g. It will get populated with 'section' or similar.
    $currentpage = '';

    // We expect $PAGE->url to exist.  It should!
    $url = $PAGE->url;

    if ($PAGE->pagetype == 'site-index') {
        $currentpage = 'frontpage';
    } else if ($PAGE->pagetype == 'my-index') {
        $currentpage = 'dashboard';
    }
    // Check if course home page.
    if (empty($currentpage)) {
        if ($url !== null) {
            // Check if this is the course view page.
            if (strstr($url->raw_out(), 'course/view.php')) {
                $currentpage = 'coursepage';

                // Check url paramaters.  Count should be 1 if course home page. Use this to check if section page.
                $urlparams = $url->params();

                // Allow the block to display on course sections too if the relevant setting is on.
                if ((count($urlparams) > 1) && (array_key_exists('section', $urlparams))) {
                    $currentpage = 'coursesectionpage';
                }
            }
        }
    }

    return $currentpage;
}

function theme_cyber_range_add_menu_icons($items) {

    if (!count($items)) {
        return $items;
    }

    for ($i = 0; $i < count($items); $i++) {
        switch ($items[$i]['key']) {
            case 'categories': 
                $items[$i]['ekc'] = 'book-circlebkgd';
                break; 
            case 'home':
                $items[$i]['ekc'] = 'home-circlebkgd';
                break;
            case 'siteadminnode':
                $items[$i]['ekc'] = 'gear';
                break;
            case 'courses':
                $items[$i]['ekc'] = 'document-circlebkgd';
                break;
            case 'mycourses':
                $items[$i]['ekc'] = 'document-circlebkgd';
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