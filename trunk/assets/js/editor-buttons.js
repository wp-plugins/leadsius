(function() {

    tinymce.create('tinymce.plugins.InsightsButtons', {
        init : function(ed, url) {
            ed.addButton( 'button_leadsius_forms', {
                title : 'Add Leadsius shortcode',
                image : '../wp-content/plugins/leadsius-wp-plugin/assets/img/Leadsius_wp_icon.png',
                onclick : function() {

                    var popup = jQuery('<div/>', { 'id': 'leadsius-forms-popup' }).css({
                        'background': '#fff',
                        'position': 'fixed',
                        'left': '50%',
                        'top': '50%',
                        'border': '1px solid #000',
                        'border-radius': '5px',
                        'padding': '15px',
                        'width': '300px',
                        'height': '200px',
                        'margin-left': '-166px',
                        'margin-top': '-116px',
                        'z-index': 10000000
                    });
                    var formsList = '';

                    for ( var i in LeadsiusForms )
                    {
                        var f = LeadsiusForms[i];

                        var fname='';
                        if(f.type=='webform')
                        {
                            fname='Web form';
                        }
                        else if(f.type=='cta'){
                            fname='CTA';
                        }
                        formsList += '<li form-id="' + f.id + '" form-type="'+f.type+'" style="cursor: pointer; background: #eee; padding: 2px 3px; margin: 1px;">' +fname+': ['+f.name +']</li>';
                    }

                    popup.html('<p style="margin: 0px 0px 10px;"><strong>Choose Leadsius shortcode:</strong></p>' +
                        '<ul style="max-height: 130px; overflow: auto; border: 1px solid #333;">' +
                            formsList +
                        '</ul>' +
                        '<a class="close" style="background: #ddd; display: inline-block; padding: 3px 5px; border: 1px solid #000; cursor: pointer; color: #000; font-weight: bold;">Close</a>'
                    );

                    jQuery('body').append(popup);

                    popup.find(".close").click(function(){
                        popup.remove();
                    });
                    popup.find("li").click(function(){
                        popup.remove();
                       if(jQuery(this).attr("form-type")=='webform'){

                         ed.selection.setContent('[leadsius-form id="' + jQuery(this).attr("form-id") + '"]');
                       }
                       else{

                         ed.selection.setContent('[leadsius-cta id="' + jQuery(this).attr("form-id") + '"]');
                       }
                       
                    });


                }
            });
        },

        createControl : function(n, cm) {
            switch (n) {
                case 'button_leadsius_forms':

                    var mlb = cm.createListBox('insights_leadsius_forms_box', {
                        title : 'Forms',
                        onselect : function(v) {
                            tinyMCE.activeEditor.selection.setContent('[leadsius-form id="' + v + '"]');
                            //tinyMCE.activeEditor.windowManager.alert('Value selected:' + v);
                        }
                    });

                    for ( var i in LeadsiusForms )
                    {
                        var f = LeadsiusForms[i];
                        mlb.add(f.name, f.id, f.type);
                    }

                    return mlb;
            }

            return null;
        }
    });

    tinymce.PluginManager.add( 'insights_buttons_script', tinymce.plugins.InsightsButtons );
})();