<?php

function insights_set_widget_icons()
{
    $insightsWidgetIcons = array(
        'fa-download' => 'Download',
        'fa-bookmark' => 'Bookmark',
        'fa-calendar' => 'Calendar',
        'fa-envelope' => 'Contact',
        'fa-chevron-circle-right' => 'Link'

    );
    $insightsWidgetIconsSource = array(
        'fa-download' => '<i class="fa fa-cloud-download"></i>',
        'fa-bookmark' => '<i class="fa fa-bookmark"></i>',
        'fa-calendar' => '<i class="fa fa-calendar"></i>',
        'fa-envelope' => '<i class="fa fa-envelope"></i>',
        'fa-chevron-circle-right' => '<i class="fa fa-chevron-circle-right"></i>'
    );

    if ( function_exists('insights_get_icons_extension') )
    {
        $extension = insights_get_icons_extension();
        foreach ($extension['icons'] as $key => $value)
        {
            $insightsWidgetIcons[$key] = $value;
        }
        foreach ($extension['icons-source'] as $key => $value)
        {
            $insightsWidgetIconsSource[$key] = $value;
        }
    }

    $GLOBALS['insights_widget_icons'] = $insightsWidgetIcons;
    $GLOBALS['insights_widget_icons_source'] = $insightsWidgetIconsSource;
}

add_action('wp_loaded','insights_set_widget_icons', 100);