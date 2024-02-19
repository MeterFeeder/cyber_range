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

    $baseurl = '/elements/dist/elements/browser/';

    $doc = new DOMDocument();
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
    $templatecontext['baseurl'] = "/theme/cyber_range/$baseurl";
    return $templatecontext;
    // $templatecontext->css;
}


function theme_cyber_range_get_fontawesome_icon_map()
{
    return [                                                                                                                        
        'theme_cyber_range:i/dashboard' => 'home-circlebkgd',                                                                                       
        'theme_cyber_range:t/selected' => 'fa-check',                                                                                       
        'theme_cyber_range:t/subscribed' => 'fa-envelope-o',                                                                                
        'theme_cyber_range:t/unsubscribed' => 'fa-envelope-open-o',                                                                         
    ]; 
}