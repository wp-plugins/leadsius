<?php

function shortcode_leadsius_form( $atts, $value )
{
	global $leadsiusAPIGateway,$insights_widget_icons_source;

	$atts = shortcode_atts( array( 'id' => 0 ), $atts );


	$forms=get_option('widget_widget_leadsius_form');

	$use_form=$forms[$atts['id']];

	$badge=$use_form['badge'];
	$body=$use_form['body'];
	$form_leadsius=$use_form['form'];
	$icon=$use_form['icon'];
	$image=$use_form['image'];
	$style=$use_form['style'];
	$title=$use_form['title'];
	$color=$use_form['color'];
	
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
		<div class="insights-cta-widget-naked widget-form-naked style-%s color-%s" >
		%s
		<h3 class="title">%s</h3>
		
			<div class="cont_image">
			%s
			</div>
			
			<div class="inside">
				<div class="content">
					
					<p class="body">%s</p>
				</div>
				<div class="form">
					%s
				</div>
			</div>
		</div>
		',
		$style,
		
		$color,
		
		$badge,
		nl2brC($title),
		$image,
		
		nl2brC($body),
		$leadsiusAPIGateway->getFormHtml($form_leadsius, $icon)
		);

//return '<pre>'.print_r($leadsiusAPIGateway->getForm('411')).'</pre>';
}
add_shortcode( 'leadsius-form', 'shortcode_leadsius_form' );