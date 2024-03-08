<?php

namespace theme_cyber_range\output;

use moodle_url;
use stdClass;

/**
 * Trait for core and core maintenance renderers.
 */
trait core_renderer_layout
{
    public function course_layout() {
        global $COURSE;
        $themesettings = \theme_adaptable\toolbox::get_settings();

        $sidepostdrawer = true;
        $movesidebartofooter = !empty(($themesettings->coursepagesidebarinfooterenabled)) ? 2 : 1;
        if ((!empty($movesidebartofooter)) && ($movesidebartofooter == 2)) {
            $sidepostdrawer = false;
        }

        // Include header.
        // $this->yesheader($sidepostdrawer);

        // Include secondary navigation.
        [$secondarynavigation, $overflow] = ['', '']; // $this->secondarynav();

        // Definition of block regions for top and bottom.  These are used in potentially retrieving
        // any missing block regions.
        $blocksarray = [
            ['settingsname' => 'coursepageblocklayoutlayouttoprow',
             'classnamebeginswith' => 'course-top-', ],
            ['settingsname' => 'coursepageblocklayoutlayoutbottomrow',
             'classnamebeginswith' => 'course-bottom-', ],
        ];

        echo '<div id="maincontainer" class="container outercont">';
        // echo $this->get_news_ticker();
        // echo $this->page_navbar();
        echo '<div id="page-content" class="row">';

        // If course page, display course top block region.
        if (!empty($themesettings->coursepageblocksenabled)) {
            echo '<div id="frontblockregion" class="container">';
            echo '<div class="row">';
            echo $this->get_block_regions('coursepageblocklayoutlayouttoprow', 'course-top-');
            echo '</div>';
            echo '</div>';
        }

        echo '<div id="region-main-box" class="col-12">';
        echo '<section id="region-main">';

        if (!empty($themesettings->tabbedlayoutcoursepage)) {
            // Use Adaptable tabbed layout.
            $currentpage = theme_adaptable_get_current_page();

            $taborder = explode('-', $themesettings->tabbedlayoutcoursepage);

            echo '<main id="coursetabcontainer" class="tabcontentcontainer">';

            $sectionid = optional_param('sectionid', 0, PARAM_INT);
            $section = optional_param('section', 0, PARAM_INT);
            if ((!empty($themesettings->tabbedlayoutcoursepagelink)) &&
                (($sectionid) || ($section))) {
                $courseurl = new moodle_url('/course/view.php', ['id' => $COURSE->id]);
                echo '<div class="linktab"><a href="' . $courseurl->out(true) . '"><i class="fa fa-th-large"></i></a></div>';
            }

            $tabcount = 0;
            foreach ($taborder as $tabnumber) {
                if ($tabnumber == 0) {
                    $tabname = 'tab-content';
                    $tablabel = get_string('tabbedlayouttablabelcourse', 'theme_adaptable');
                } else {
                    $tabname = 'tab' . $tabnumber;
                    $tablabel = get_string('tabbedlayouttablabelcourse' . $tabnumber, 'theme_adaptable');
                }

                $checkedstatus = '';
                if (($tabcount == 0 && $currentpage == 'coursepage') ||
                    ($currentpage != 'coursepage' && $tabnumber == 0)) {
                    $checkedstatus = 'checked';
                }

                $extrastyles = '';

                if ($currentpage == 'coursepage') {
                    $extrastyles = ' style="display: none"';
                }

                echo '<input id="' . $tabname . '" type="radio" name="tabs" class="coursetab" ' . $checkedstatus . ' >' .
                    '<label for="' . $tabname . '" class="coursetab" ' . $extrastyles . '>' . $tablabel . '</label>';

                $tabcount++;
            }

            /* Basic array used by appropriately named blocks below (e.g. course-tab-one).  All this is to re-use existing
                functionality and the non-use of numbers in block region names. */
            $wordtonumber = [1 => 'one', 2 => 'two'];

            foreach ($taborder as $tabnumber) {
                if ($tabnumber == 0) {
                    echo '<section id="adaptable-course-tab-content" class="adaptable-tab-section tab-panel">';

                    // echo $this->get_course_alerts();
                    if (!empty($themesettings->coursepageblocksliderenabled)) {
                        echo $this->get_block_regions('customrowsetting', 'news-slider-', '12-0-0-0');
                    }

                    echo $this->course_content_header();
                    if (!empty($secondarynavigation)) {
                        echo $secondarynavigation;
                    }
                    if (!empty($overflow)) {
                        echo $overflow;
                    }
                    echo $this->main_content();
                    echo $this->course_content_footer();

                    echo '</section>';
                } else {
                    echo '<section id="adaptable-course-tab-' . $tabnumber . '" class="adaptable-tab-section tab-panel">';

                    echo $this->get_block_regions(
                        'customrowsetting',
                        'course-tab-' . $wordtonumber[$tabnumber] . '-',
                        '12-0-0-0'
                    );
                    echo '</section>';
                }
            }
            echo '</main>';
        } else {
            // echo $this->get_course_alerts();
            if (!empty($themesettings->coursepageblocksliderenabled)) {
                echo $this->get_block_regions('customrowsetting', 'news-slider-', '12-0-0-0');
            }
            echo $this->course_content_header();
            if (!empty($secondarynavigation)) {
                echo $secondarynavigation;
            }
            if (!empty($overflow)) {
                echo $overflow;
            }
            echo $this->main_content();
            echo $this->course_content_footer();
        }

        /* Check if the block regions are disabled in settings.  If it is and there were any blocks
           assigned to those regions, they would obviously not display.  This will allow to override
           the call to get_missing_block_regions to just display them all. */
        $displayall = false;
        if (empty($themesettings->coursepageblocksenabled)) {
            $displayall = true;
        }

        /* Check here if sidebar is configured to be in footer as we want to include
           the sidebar information in the main content. */
        if ($movesidebartofooter == 1) {
            echo '</section>';
            echo '</div>';

            /* Get any missing blocks from changing layout settings.  E.g. From 4-4-4-4 to 6-6-0-0, to recover
               what was in the last 2 spans that are now 0. */
            // echo $this->get_missing_block_regions($blocksarray, 'col-12', $displayall);
        }

        // If course page, display course bottom block region.
        if (!empty($themesettings->coursepageblocksenabled)) {
            echo '<div id="frontblockregion" class="container">';
            echo '<div class="row">';
            echo $this->get_block_regions('coursepageblocklayoutlayoutbottomrow', 'course-bottom-');
            echo '</div>';
            echo '</div>';
        }

        if ($movesidebartofooter == 2) {
            $hassidepost = $this->page->blocks->region_has_content('side-post', $this);

            if ($hassidepost) {
                echo $this->blocks('side-post', 'col-12 d-print-none');
            }

            /* Get any missing blocks from changing layout settings.  E.g. From 4-4-4-4 to 6-6-0-0, to recover
               what was in the last 2 spans that are now 0. */
            echo $this->get_missing_block_regions($blocksarray, [], $displayall);

            echo '</section>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';

        // Include footer.
        // $this->yesfooter();

        if (!empty($themesettings->tabbedlayoutcoursepage)) {
            if (!empty($themesettings->tabbedlayoutcoursepagetabpersistencetime)) {
                $tabbedlayoutcoursepagetabpersistencetime = $themesettings->tabbedlayoutcoursepagetabpersistencetime;
            } else {
                $tabbedlayoutcoursepagetabpersistencetime = 30;
            }
            $this->page->requires->js_call_amd('theme_adaptable/utils', 'init', ['currentpage' => $currentpage,
                'tabpersistencetime' => $tabbedlayoutcoursepagetabpersistencetime, ]);
        }
    }
}