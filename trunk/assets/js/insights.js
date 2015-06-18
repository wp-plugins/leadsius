(function($) {
    $(document).ready(function(){

        $(".article-tabbing").each(function(){
            var widget = $(this);
            function SetWidgetExtraClasses()
            {
                if (widget.width() > 400)
                    widget.removeClass('thin-style');
                else
                    widget.addClass('thin-style');
            }
            SetWidgetExtraClasses();
            $(window).load(function(){
                SetWidgetExtraClasses();
            }).resize(function(){
                SetWidgetExtraClasses();
            });
        });

        /* Global Modules */
        $('select').each(function(){
            var select = $(this);

            select.val( select.children('option[selected=selected]').attr('value') );

            var combobox = $('<div/>', { 'class': 'insights-combobox', 'rel-select': select.attr('id') });
            var ul = $('<ul/>');
            var value = $('<div/>', { 'class': 'value', 'text': select.find(':selected').text() });
            var arrow = $('<div/>', { 'class': 'arrow' });

            if ( typeof select.attr('id') != 'undefined' )
            {
                combobox.attr('id', select.attr('id') + '-combo');
            }

            combobox.append(value).append(arrow).append(ul);

            select.children('option').each(function(){
                if ( $(this).attr('data-exclude') != 'yes' )
                {
                    ul.append( $('<li/>', { 'optval': $(this).attr('value'), 'text': $(this).text() }) );
                }
            });

            $(this).after(combobox).addClass('has-combobox');
        });

        $(".insights-combobox").each(function(){
            var combobox = $(this);
            var list = combobox.find('ul');
            var input = combobox.find('input');
            var inputText = combobox.find('.combo-text');
            var value = combobox.find(".value");

            value.click(function(e){
                e.stopPropagation();
                list = combobox.find('ul');
                list.toggle();
            });
            combobox.find(".arrow").click(function(e){
                e.stopPropagation();
                list = combobox.find('ul');
                list.toggle();
            });
            combobox.delegate("li", "click", function(e){
                var val = $(this).attr('optval');
                var text = $(this).text();
                value.text(text);
                input.val(val);
                inputText.val(text);

                if (combobox.attr('rel-select') != 'undefined')
                {
                    $('#' + combobox.attr('rel-select')).val(val);
                }

                if ( typeof combobox.attr('id') != 'undefined' )
                {
                    var callback = combobox.attr('id').replace(/-/g, "_") + '_callback';
                    if (typeof window[callback] == 'function')
                    {
                        eval(callback + "('" + val + "')");
                    }
                }
            });
            $(document).click(function(){
                list.hide();
            });
        });
        /* End Global Modules */

        /* Article Tabbing */
        $(".article-tabbing").each(function(){
            var widget = $(this);
            widget.find(".tab-title").click(function(){
                $(this).addClass("active").siblings(".tab-title").removeClass("active");
                widget.find(".post").hide();
                widget.find(".post-" + $(this).attr('data-type')).show();
            });

            widget.find(".tab-title").eq(0).trigger('click');
        });
        /* End Article Tabbing */

        /* Input Labels (rich placeholders) */
        $('#insights-section .text-field').each(function(){
            var input = $(this).children('input');
            var label = $(this).children('label');

            if ($.trim(input.val()) != '')
            {
                label.hide();
            }

            label.click(function(){
                input.focus();
            });
            input.focus(function(){
                label.hide();
            }).blur(function(){
                if ($.trim($(this).val()) == '')
                {
                    label.show();
                }
            });
        });
        /* End Input Labels (rich placeholders) */

//        $('#post-associated-form label').each(function(){
//            var label = $(this);
//            var input = label.parent().find('input, textarea');
//
//            input.focus(function(){
//                label.hide();
//            }).blur(function(){
//                if ( $.trim(input.val()) == '' )
//                {
//                    label.show();
//                }
//            });
//
//            label.click(function(){
//                input.focus();
//            });
//
//            if ( $.trim(input.val()) != '' || (typeof input.attr('placeholder') != 'undefined' && $.trim(input.attr('placeholder')) != '') )
//            {
//                label.hide();
//            }
//        });

    });
})( jQuery );

function insights_selector_callback(val)
{
    window.location.href = "?type=" + val;
}
function insights_search_category_combo_callback(val)
{
    $('#insights-search-form').submit();
}
function insights_search_tag_combo_callback(val)
{
    $('#insights-search-form').submit();
}