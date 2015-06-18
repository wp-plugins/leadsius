<?php

class Widget_Leadsius_Form extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'widget_leadsius_form',
            __('Leadsius Form', 'insights'),
            array( 'description' => __( 'Add Leadsius forms into your site', 'insights' ), )
        );
    }

    public function widget( $args, $instance )
    {
        global $leadsiusAPIGateway, $insights_widget_icons_source;

        //$type = 'just-color-small-text';
        //$icon = isset($instance['icon']) ? $instance['icon'] : '';
        $name = isset($instance['name']) ? $instance['name'] : '';
        $button_color = isset($instance['button_color']) ? $instance['button_color'] : '';
        $custom_css = isset($instance['custom_css']) ? $instance['custom_css'] : '';
        $custom_javascript = isset($instance['custom_javascript']) ? $instance['custom_javascript'] : '';
        //$badge = isset($instance['badge']) ? $instance['badge'] : '';
        $style = isset($instance['style']) ? $instance['style'] : '';
        $color = isset($instance['color']) ? $instance['color'] : '';
        $title = isset($instance['title']) ? $instance['title'] : '';
        $body = isset($instance['body']) ? $instance['body'] : '';
        $category = isset($instance['category']) ? $instance['category'] : '';
        $form = isset($instance['form']) ? $instance['form'] : 0;
        $pages = explode(";", isset($instance['pages']) ? $instance['pages'] : '');
        $image = isset($instance['image']) ? $instance['image'] : '';

        if (!is_insights_widget_available_here($pages))
        {
            return false;
        }

        /*if ($badge != '')
        {
            $badge = sprintf('<div class="widget-badge widget-badge-%s"><div class="widget-badge-inside">%s</div></div>', strtolower($badge), $badge);
        }*/
        /*if ($icon != '')
        {
            $icon = $insights_widget_icons_source[$icon];
        }*/

        if (trim($image) != '')
        {
            $image = sprintf('<img src="%s" alt="Background" class="ls-bkg" />', $image);
        }

        echo $args['before_widget'];
        printf( '
            <div class="insights-cta-widget widget-form ls-style-%s ls-color-%s">

            <div class="ls-cont_image">
                %s
            </div>

            <div class="ls-inside">
                <div class="ls-content">
                    <h3 class="ls-title">%s</h3>

                    <p class="ls-body">%s</p>
                </div>
                <div class="ls-form">
                    %s
                </div>
            </div>
        </div>
        %s
        %s
        ',
            $style,

            $color,



            $image,
            nl2brC($title),
            nl2brC($body),
            $leadsiusAPIGateway->getFormHtml($form, '', $button_color),
            '<style>'.$custom_css.'</style>',
            '<script>'.$custom_javascript.'</script>'
        );
        echo $args['after_widget'];
    }

    public function form( $instance )
    {

        //var_dump($instance);
        global $insights_widget_icons,$select_colors;

        $styles = array( "Naked", "Box");
        $formId = uniqid();


        //$badges = array( "Free", "Hot", "New", "Featured" );

        $f = isset($instance['form']) ? $instance['form'] : '';
        $name = isset($instance['name']) ? $instance['name'] : '';
        $button_color = isset($instance['button_color']) ? $instance['button_color'] : '';
        //$icon = isset($instance['icon']) ? $instance['icon'] : '';
        $custom_css = isset($instance['custom_css']) ? $instance['custom_css'] : '';
        $custom_javascript = isset($instance['custom_javascript']) ? $instance['custom_javascript'] : '';
        //$badge = isset($instance['badge']) ? $instance['badge'] : '';
        $style = isset($instance['style']) ? $instance['style'] : '';
        $color = isset($instance['color']) ? $instance['color'] : '';
        //$selectedColor = isset($instance['color']) ? $instance['color'] : $colors[0];
        $category = isset($instance['category']) ? $instance['category'] : '';
        $title = isset($instance['title']) ? $instance['title'] : '';
        $body = isset($instance['body']) ? $instance['body'] : '';
        $pages = isset($instance['pages']) ? explode(";", $instance['pages']) : '';
        $image = isset($instance['image']) ? $instance['image'] : '';

        global $insights_leadsius_forms;
        ?>

        <div class="widget-config-<?php echo $formId; ?>">
            <?php if ( isset($insights_leadsius_forms->webforms) && is_array($insights_leadsius_forms->webforms) && count($insights_leadsius_forms->webforms) != 0 ): ?>
                <div class="widget-config-<?php echo $formId; ?>">
                    <p>
                        <label><?php _e('Form', 'insights'); ?></label>
                        <select class="widefat" name="<?php echo $this->get_field_name('form'); ?>">
                            <?php foreach($insights_leadsius_forms->webforms as $form): ?>
                                <option value="<?php echo $form->id; ?>" <?php echo $f == $form->id ? 'selected="selected"' : ''; ?>><?php echo $form->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                </div>
            <?php endif; ?>
            <p>
                <label><?php _e('Name', 'insights'); ?></label>
                <textarea class="widefat" name="<?php echo $this->get_field_name('name'); ?>"><?php echo $name; ?></textarea>
            </p>
            <p>
                <label><?php _e('Title', 'insights'); ?></label>
                <textarea class="widefat" name="<?php echo $this->get_field_name('title'); ?>"><?php echo $title; ?></textarea>
            </p>
            <p>
                <label><?php _e('Body', 'insights'); ?></label>
                <textarea class="widefat" name="<?php echo $this->get_field_name('body'); ?>"><?php echo $body; ?></textarea>
            </p>

            <p>
                <input type="hidden" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $image; ?>" />
                <a class="button button-primary scwu"><?php _e('Select image', 'insights'); ?></a>
                <?php if ($image != ''): ?>
                    <img src="<?php echo $image; ?>" style="max-width: 150px; display: block; margin: 5px 0px;" />
                    <span class="remove-image" style="cursor: pointer; color: #ff0000;"><?php _e('Remove Image', 'insights'); ?></span>
                <?php endif; ?>
            </p>

            <p>
                <label><?php _e('Style', 'insights'); ?></label>
                <select class="widefat style" name="<?php echo $this->get_field_name('style'); ?>">

                    <?php foreach ($styles as $value): ?>
                        <option value="<?php echo $value; ?>"  <?php echo $value == $style ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>


                </select>
            </p>
            <p class="select_colors" <?php if($style=='Box'){?>style="display:block"<?php }else{ ?> style="display:none" <?php } ?>>
                <label><?php _e('Colors', 'insights'); ?></label>
                <select class="widefat" name="<?php echo $this->get_field_name('color'); ?>">
                    <option value="none">-- none --</option>
                    <?php foreach ($select_colors as $value): ?>
                        <option value="<?php echo $value; ?>"  <?php echo $value == $color ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>


                </select>
            </p>
           <!-- <p>
                <label><?php //_e('Icon', 'insights'); ?></label>
                <select class="widefat" name="<?php //echo $this->get_field_name('icon'); ?>">
                    <option value="">-- none --</option>
                    <?php //foreach($insights_widget_icons as $key => $value): ?>
                        <option value="<?php //echo $key; ?>" <?php //echo $key == $icon ? 'selected="selected"' : ''; ?>><?php //echo $value; ?></option>
                    <?php //endforeach; ?>
                </select>
            </p> -->



            <p>
                <label><?php _e('Button color', 'insights'); ?></label>


                <input id="ls-button-color" class="ls-button-color widefat"  value="<?php echo $button_color; ?>" name="<?php echo $this->get_field_name('button_color'); ?>"/>

            </p>
            <!--<p>
                <label><?php //_e('Badge', 'insights'); ?></label>
                <select class="widefat" name="<?php //echo $this->get_field_name('badge'); ?>">
                    <option value="">-- none --</option>
                    <?php //foreach($badges as $value): ?>
                        <option value="<?php //echo $value; ?>" <?php //echo $value == $badge ? 'selected="selected"' : ''; ?>><?php //echo $value; ?></option>
                    <?php //endforeach; ?>
                </select>
            </p> -->
            <p>
                <label><?php _e('Custom CSS', 'insights'); ?></label>

                <textarea class="widefat" name="<?php echo $this->get_field_name('custom_css'); ?>"><?php echo $custom_css; ?></textarea>


            </p>
            <p>
                <label><?php _e('Custom Javascript', 'insights'); ?></label>

                <textarea class="widefat" name="<?php echo $this->get_field_name('custom_javascript'); ?>"><?php echo $custom_javascript; ?></textarea>


            </p>
            <p>
                <label><?php _e('Display', 'insights'); ?></label>
                <?php where_to_show_selector($this->get_field_name('pages'), $pages); ?>
            </p>
            <p>
                <input type="hidden" name="<?php echo $this->get_field_name('category'); ?>" value="widget" />

            </p>
        </div>

        <script type="text/javascript">

            var imageSourceField;


            (function($) {


                $(".widget-config-<?php echo $formId; ?> .ls-button-color").spectrum({

                    preferredFormat: "hex",
                    showInput: true

                });

                var duplicated = $('.widget-config-<?php echo $formId; ?> .sp-replacer')[2];
                $(duplicated).hide();

                $(".widget-config-<?php echo $formId; ?>").each(function(){
                    var form = $(this);
                    var button = form.find(".scwu");

                    form.find(".color-options label").click(function(){
                        $(this).css('border-color', '#777').siblings('label').css('border-color', '#fff');
                    });

                    button.unbind('click').click(function(e) {
                        e.preventDefault();

                        imageSourceField = $(this).parent().find("input");
                        tb_show("","media-upload.php?TB_iframe=true");
                        console.log(imageSourceField);

                        window.send_to_editor = function(html) {
                            if (imageSourceField)
                            {
                                console.log(html);
                                if($("img", html).attr("src")!=undefined)
                                {
                                    var url = $("img", html).attr("src");
                                }
                                else
                                {
                                    var url = $(html).attr("src");
                                }

                                console.log(url);
                                imageSourceField.val(url);
                                imageSourceField.parent().append($("<img/>", { "src": url }).css('max-width', 150).css('display', 'block').css('margin', '5px 0px'));
                                imageSourceField.parent().append($("<span/>", { "class": 'remove-image', 'text': 'Remove Image' }).css('cursor', 'pointer').css('color', '#ff0000'));
                                //imageSourceField.parent().append($("<img/>", { "src": url }).css('max-width', 150).css('display', 'block').css('margin', '5px 0px'));
                                //imageSourceField.parent().append($("<span/>", { "class": 'remove-image', 'text': 'Remove Image' }).css('cursor', 'pointer').css('color', '#ff0000'));

                                //imageSourceField = null;
                                tb_remove();
                            }

                        };

                    });

                    form.delegate(".remove-image", "click", function(){
                        if (confirm('Are you sure?'))
                        {
                            $(this).siblings("input").val("");
                            $(this).siblings("img").remove();
                            $(this).remove();
                        }
                    });
                });


                /*** change style . if style=='Box-Color'  show colors dropdown****/

                $(document).on('change', '.style', function(event) {
                    event.preventDefault();
                    /* Act on the event */


                    if($(this).find('option:selected').val()=="Box"){

                        $('.select_colors').show();

                    }else{

                        $('.select_colors ').hide();

                        $('.select_colors select option[value="none"]').attr('selected','selected');
                    }

                });

                $(document).ready(function(){



                });



            })(jQuery);
        </script>
    <?php
    }

    public function update( $new_instance, $old_instance )
    {
        $instance = array();
        $instance['form'] = ( ! empty( $new_instance['form'] ) ) ? strip_tags( $new_instance['form'] ) : '';
        $instance['name'] = ( ! empty( $new_instance['name'] ) ) ? strip_tags( $new_instance['name'] ) : '';
        //$instance['icon'] = ( ! empty( $new_instance['icon'] ) ) ? strip_tags( $new_instance['icon'] ) : '';
        $instance['button_color'] = ( ! empty( $new_instance['button_color'] ) ) ? strip_tags( $new_instance['button_color'] ) : '';
        $instance['custom_css'] = ( ! empty( $new_instance['custom_css'] ) ) ? strip_tags( $new_instance['custom_css'] ) : '';
        $instance['custom_javascript'] = ( ! empty( $new_instance['custom_javascript'] ) ) ? strip_tags( $new_instance['custom_javascript'] ) : '';
        //$instance['badge'] = ( ! empty( $new_instance['badge'] ) ) ? strip_tags( $new_instance['badge'] ) : '';
        $instance['style'] = ( ! empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : '';
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';
        $instance['color'] = ( ! empty( $new_instance['color'] ) ) ? strip_tags( $new_instance['color'] ) : '';
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['body'] = ( ! empty( $new_instance['body'] ) ) ? $new_instance['body'] : '';
        $instance['pages'] = ( ! empty( $new_instance['pages'] ) ) ? implode(";", $new_instance['pages'] ) : '';
        $instance['image'] = ( ! empty( $new_instance['image'] ) ) ? strip_tags( $new_instance['image'] ) : '';

        return $instance;
    }
}

function register_leadsius_form_widget()
{
    register_widget( 'Widget_Leadsius_Form' );
}
add_action( 'widgets_init', 'register_leadsius_form_widget' );