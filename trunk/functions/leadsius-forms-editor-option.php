<?php

add_action( 'admin_init', 'insights_tinymce_buttons' );

function insights_tinymce_buttons()
{
    if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) )
    {
        add_filter( 'mce_buttons', 'insights_register_tinymce_button' );
        add_filter( 'mce_external_plugins', 'insights_add_tinymce_button' );
    }
}

function insights_register_tinymce_button( $buttons )
{
    array_push( $buttons, "button_leadsius_forms" );

    return $buttons;
}

function insights_add_tinymce_button( $plugin_array )
{
    $plugin_array['insights_buttons_script'] = plugins_url( '/assets/js/editor-buttons.js', dirname(__FILE__) ) ;

    return $plugin_array;
}

function add_leadsius_forms_in_admin_header()
{








    echo '<script type="text/javascript">';
    echo 'LeadsiusForms = new Array();';

    if(get_option('widget_widget_leadsius_form') ){


        foreach (get_option('widget_widget_leadsius_form') as $key=>$form){

            if($form['category']=="shortcode"){

              printf( " LeadsiusForms.push( { 'id': %d ,'name': '%s','type':'webform' } ); ", $key, $form['name'] );
          }
      }
  }

   if(get_option('widget_widget_cta_insights') ){


        foreach (get_option('widget_widget_cta_insights') as $key=>$form){

            if($form['category']=="shortcode"){

              printf( " LeadsiusForms.push( { 'id': %d ,'name': '%s','type':'cta' } ); ", $key, $form['name'] );
          }
      }
  }





  echo '</script>';
}
add_action( 'admin_head', 'add_leadsius_forms_in_admin_header' );