(function($) {
    $(document).ready(function(){



        $("form.webform").delegate(".form-result", 'click', function(){
            $(this).remove();
        });
        $('.lf-submit').each(function(){


            var $el=$(this);
            if (!$(this).hasClass('lf-skip'))
            {
                $(this).click(function(e){
                    e.preventDefault();

                    var btn = $(this);
                    var form = btn.parents('form').eq(0);
                    var fields = form.find('input');
                    var hasErrors = false;
                    form.find('.form-result').remove();

                    fields.each(function(){
                        var type = $(this).attr('type');
                        var value = $(this).val();

                        if ( $.inArray( type, ['hidden', 'checkbox', 'radio'] ) == -1 )
                        {
                            if ( type == 'email' && !IsValidEmail(value) )
                            {
                                hasErrors = true;
                            }
                            else if ( $.trim(value) == '' )
                            {
                                hasErrors = true;
                            }
                        }
                    });

                    if ( hasErrors )
                    {
                        btn.after( '<div class="form-result">Please fill in all the fields and insert a valid email address!</div>' );
                    }
                    else
                    {

                        $.ajax({
                            url: btn.attr('data-submit'),
                            type: "POST",
                            data: { webform: form.serialize(), idContact: '' },
                            dataType: "html",
                            async:true,
                            beforeSend: function(){

                                $('.spinner').show();
                                btn.hide();
                            },
                            success: function(res) {

                                $('.spinner').hide();
                                btn.show();

                                if ( btn.attr('data-option') == 'thankyouPage' || btn.attr('data-option') == 'redirectExternalPage' )
                                {
                                    //window.open(btn.attr('data-redirect'),'_blank');
                                    window.top.location.href = btn.attr('data-redirect');
                                    //window.location.href = btn.attr('data-redirect');
                                }
                                else if ( btn.attr('data-option') == 'alertMessage' )
                                {
                                    btn.before( '<div class="form-result">' + btn.attr('data-alert') + '</div>' );
                                }

                                var json=$.parseJSON(res);
                                $('.response_track').remove();
                                $el.parent().after('<img class="response_track ls-tracker" src="'+json.img+'" />');
                            }
                        });
                    }
                });
            }
        });

        function IsValidEmail(email)
        {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            return regex.test(email);
        }
    });
})( jQuery );
