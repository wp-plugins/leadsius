<?php

function shortcode_leadsius_cta( $atts, $value )
{
	global $leadsiusAPIGateway,$insights_widget_icons_source;

	$atts = shortcode_atts( array( 'id' => 0 ), $atts );


	$forms=get_option('widget_widget_cta_insights');

	$use_form=$forms[$atts['id']];

	$badge=$use_form['badge'];
	$body=$use_form['body'];
	
	$icon=$use_form['icon'];
	$image=$use_form['image'];
	$style=$use_form['style'];
	$title=$use_form['title'];
	$color=$use_form['color'];
	$buttonText=$use_form['button-text'];
	$buttonUrl=$use_form['button-url'];

	      if ($badge != '')
        {
            $badge = sprintf('<div class="widget-badge widget-badge-%s"><div class="widget-badge-inside">%s</div></div>', strtolower($badge), $badge);
        }
        if ($icon != '')
        {
            $icon = $insights_widget_icons_source[$icon];
        }

        if (trim($image) != '')
        {
            $image = sprintf('<img src="%s" alt="Background" class="bkg" />', $image);
        }

        
           return sprintf( '
                <div class="insights-cta-widget-naked style-%s color-%s">
                    %s
                    
                        <h3 class="title">%s</h1>

                    
                    <div class="cont_image">
                        %s
                    </div>

                    <div class="inside">

                        <div class="content">
                            <p class="body">%s</p>
                            %s
                        </div>

                    </div>
                </div>
                ', $style, $color, $badge, $title, $image, nl2brC($body), (trim($buttonText) != '' ? sprintf('<button onClick="window.location.href=\'%s\';" class="button" >%s%s</button>', $buttonUrl, $icon, $buttonText) : '')
                );
       

}
add_shortcode( 'leadsius-cta', 'shortcode_leadsius_cta' );