<?php

class LeadsiusAPIGateway {

    private $token;
    private $allForms;

    function __construct($token)
    {
        $this->token = $token;
    }

    public function getAllForms()
    {
        $this->allForms = $this->makeAPICall('webforms.json');
        if($this->allForms->total > $this->allForms->page_size){

            $this->allForms = $this->makeAPICall('webforms.json',($this->allForms->page_size*$this->allForms->total_pages));
        }

        return $this->allForms;
    }

    public function getForm($formId)
    {
        $formInfo = $this->makeAPICall( sprintf( 'webforms/%d.json', $formId) );


        //print_r($formInfo);die();
        foreach($this->allForms->webforms as $key => $form)
        {
            if ( $form->id == $formId )
            {
                $this->allForms->webforms[$key]->form_data = $formInfo;
            }
        }

        return $formInfo;
    }

    public function getFormHtml($formId, $buttonIcon = '', $buttonColor = '')
    {


        if ( isset($this->allForms->webforms) && is_array($this->allForms->webforms) )
        {


            foreach( $this->allForms->webforms as $webform )
            {


                if ( $webform->id == $formId )
                {


                    $formData = isset($webform->form_data) ? $webform->form_data : $this->getForm($formId);
                    //var_dump($formData->json_customization);die();
                    $trackingPixel=$formData->tracking_pixel;




                    if(isset($webform->customization) && strlen($formData->json_customization)==0){

                        $customization = json_decode($webform->customization);
                        //var_dump($customization->wf_button_text);die();


                        if(is_array($customization->wf_button_text)){

                            if($customization->wf_button_text[0] == ''){

                                $buttonText = 'Submit';
                            }
                            else{
                                $buttonText = $customization->wf_button_text[0];

                            }
                        }
                        else{

                            if($customization->wf_button_text == ''){

                                $buttonText = 'Submit';
                            }
                            else{
                                $buttonText = $customization->wf_button_text;

                            }
                        }
                        //$buttonText = isset($customization->wf_button_text) ? $customization->wf_button_text : 'Submit';

                    }
                    else{

                        $new_custom = json_decode($formData->json_customization);
                        //var_dump($new_custom);die();
                        if($new_custom->button_name == "")
                            $buttonText='Submit';
                        else{

                            $buttonText = $new_custom->button_name;
                        }
                    }

                    //var_dump($buttonText);die();
                    //$buttonText = 'submit';


                    $alertMessage = strip_tags(isset($webform->confirmation_option) && $webform->confirmation_option == 'alertMessage' ? $webform->alert_message : '');
                    $redirectUrl =  isset($webform->confirmation_option) && $webform->confirmation_option == 'redirectExternalPage' ?
                        $webform->webform_redirect_external_page :
                        (isset($webform->confirmation_option) && $webform->confirmation_option == 'thankyouPage' ? home_url('?thank-you='.$webform->id) : '');
                    if($buttonColor!=''){ $buttonColor = 'style="background-color : '.$buttonColor.'";'; }
                    $buttonHtml = sprintf(' <button %s class="lf-submit" data-id="%s" data-option="%s" data-alert="%s" data-redirect="%s" data-submit="%s">%s</button>', $buttonColor, $webform->id, $webform->confirmation_option, $alertMessage, $redirectUrl, $formData->submit_url, $buttonText );
                    $webformIdField = sprintf( '<input type="hidden" name="idWebform" value="%s" />', $webform->id );


                    $formulario = '';
                    if (isset($webform->html_content) && strlen($webform->json_content)==0){

                        $formulario = $webform->html_content;
                    }

                    else{

                        $json=json_decode($webform->json_content);
                        $json_custom=json_decode($webform->form_data->json_customization);


                        $toappend = '<form class="form-horizontal webform" method="POST" action="'.$formData->submit_url.'" name="webform">';


                        for($i=0;$i<count($json);$i++)
                        {

                            if(property_exists($json[$i],'description')){

                                if($json[$i]->description=='undefined' || $json[$i]->description=='' || $json[$i]->description==null)
                                {
                                    $json[$i]->description = '';
                                }
                            }

                            if ($json[$i]->type=='title')
                            {
                                $toappend .= '<h3>'.$json[$i]->text.'</h3>';
                            }
                            else if ($json[$i]->type=='body')
                            {
                                $toappend .= '<p class="ls-body">'.$json[$i]->text.'</p>';

                            }
                            else if ($json[$i]->type=='textInput' || (property_exists($json[$i],'root_type') && $json[$i]->root_type=='textInput'))
                            {

                                $toappend .= '<div class="ls-form-group"> <label for="'.$json[$i]->name.'" class="fb-required">'.$json[$i]->label.'</label><div><input name="'.$json[$i]->name.'" type="text"';

                                if(property_exists($json[$i],'validator-error')){

                                    $toappend.=' validator-error="'.$json[$i]->{'validator-error'}.'"';
                                }
                                if(property_exists($json[$i],'validator')){

                                    $toappend.=' validator="'.$json[$i]->validator.'"';
                                }

                                $toappend .= 'validator-required="'.$json[$i]->{'validator-required'}.'"  id="'.$json[$i]->name.'" class="ng-pristine ng-valid" placeholder="'.$json[$i]->placeholder.'"><p class="help-block">'.$json[$i]->description.'</p></div></div>';
                            }
                            else if( $json[$i]->type=='textArea' ||( property_exists($json[$i],'root_type') && $json[$i]->root_type=='textArea') )
                            {


                                $toappend .= '<div class="ls-form-group"> <label for="'.$json[$i]->name.'" class="fb-required">'.$json[$i]->label.'</label><div><textarea name="'.$json[$i]->name.'" type="text" validator-required="'.$json[$i]->{'validator-required'}.'" ';
                                if(property_exists($json[$i],'validator-error')){

                                    $toappend.=' validator-error="'.$json[$i]->{'validator-error'}.'"';
                                }
                                if(property_exists($json[$i],'validator')){

                                    $toappend.=' validator="'.$json[$i]->validator.'"';
                                }

                                $toappend.='id="'.$json[$i]->name.'" class="ng-pristine ng-valid" rows="6" placeholder="'.$json[$i]->placeholder.'"></textarea><p class="help-block">'.$json[$i]->description.'</p></div></div>';

                            }
                            else if( $json[$i]->type=='select' || (property_exists($json[$i],'root_type') && $json[$i]->root_type=='select')  )
                            {
                                $opts='';
                                for($h=0;$h<count($json[$i]->options);$h++){

                                    if(is_object($json[$i]->options[$h]))
                                        $opts .= '<option value="'.$json[$i]->options[$h]->id.'">'.$json[$i]->options[$h]->option.'</option>';
                                    else
                                        $opts .= '<option value="'.$json[$i]->options[$h].'">'.$json[$i]->options[$h].'</option>';
                                }
                                $toappend .= '<div class="ls-form-group ng-scope"><label for="'.$json[$i]->name.'" class="">'.$json[$i]->label.'</label><div class=""><select name="'.$json[$i]->name.'"  id="'.$json[$i]->name.'" class=""   validator="/.*/" validator-error="">'.$opts.'</select><p class="help-block">'.$json[$i]->description.'</p></div></div>';

                            }
                            else if($json[$i]->type=='radio' || (property_exists($json[$i],'root_type') &&  $json[$i]->root_type=='radio'))
                            {

                                $opts='';
                                for($h=0;$h<count($json[$i]->options);$h++){
                                    if(is_object($json[$i]->options[$h]))
                                        $opts .= '<div class=""><label class="radio"><input name="'.$json[$i]->name.'" value="'.$json[$i]->options[$h]->id.'" type="radio" class="">'.$json[$i]->options[$h]->option.'</label> </div>';
                                    else
                                        $opts .= '<div class=""><label class="radio"><input name="'.$json[$i]->name.'" value="'.$json[$i]->options[$h].'" type="radio" class="">'.$json[$i]->options[$h].'</label> </div>';
                                }

                                $toappend .= '<div class="ls-form-group"> <label for="'.$json[$i]->name.'" class="radio_label" ng-class="">'.$json[$i]->label.'</label> <div class="">'.$opts.' <p class="help-block ng-binding">'.$json[$i]->description.'</p> </div> </div>';

                            }
                            else if($json[$i]->type=='checkbox' ||  (property_exists($json[$i],'root_type') && $json[$i]->root_type=='checkbox' ) )
                            {

                                $opts='';

                                for($h=0;$h<count($json[$i]->options);$h++)
                                {
                                    if(is_object($json[$i]->options[$h]))
                                        $opts .= '<div class="checkbox"> <label class=""><input name="'.$json[$i]->name.'" type="checkbox"  value="'.$json[$i]->options[$h]->id.'" class="">'.$json[$i]->options[$h]->option.'</label> </div>';
                                    else
                                        $opts .= '<div class="checkbox"> <label class=""><input name="'.$json[$i]->name.'" type="checkbox"  value="'.$json[$i]->options[$h].'" class="">'.$json[$i]->options[$h].'</label> </div>';

                                }
                                $toappend .= '<div class="ls-form-group"> <label for="'.$json[$i]->name.'" class="checkbox_label">'.$json[$i]->label.'</label> <div class=""> <input type="hidden" ';
                                if(property_exists($json[$i],'validator-error')){

                                    $toappend.=' validator-error="'.$json[$i]->{'validator-error'}.'"';
                                }
                                if(property_exists($json[$i],'validator-required')){

                                    $toappend.=' validator="'.$json[$i]->{'validator-required'}.'" ';
                                }

                                 $toappend.= ' class="">'.$opts.'<p class="help-block ng-binding">'.$json[$i]->description.'</p></div></div>';


                            }
                            /*else if($json[$i]->type=='submit')
                            {
                                $if_color='';
                                 if($json[1]->button_color!='')
                                 {
                                     $if_color='style="background-color:'.$json[1]->button_color.'"';
                                 }
                                $toappend .= '<button type="button" class="ls-button" '.$if_color.' id="ls-submit"><i class="'.$json[$i]->class.'"></i><span>'.$json[$i]->label.'</span></button><div class="ls-spinner"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>';
                                $url_sub = $json[$i]->dir;
                            }*/


                        }


                        $toappend.=' </form>';
                        $toappend.=$json_custom->custom_css.$json_custom->custom_js;
                        $formulario = $toappend;





                    }

                    return str_replace( '</form>', '<div class="spinner" style="display:none"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>'.$buttonHtml . $webformIdField . ' </form>'.'<img class="ls-tracker" src="'.$trackingPixel.'" />', $formulario );
                }
            }
        }

        return null;
    }

    private function makeAPICall($action,$page_size=null)
    {
        if($page_size!=null)
            $page_size='&page_size='.$page_size;
        $url = sprintf('https://api.leadsius.com/%s?api_key=%s%s', $action, $this->token,$page_size);
        //$url = sprintf('http://localhost/leadsius-api/web/app_dev.php/%s?api_key=%s&page_size=40', $action, $this->token);

        $ctx = stream_context_create( array('https'=> array( 'timeout' => 15 ) ));

        $res = @file_get_contents( $url, false, $ctx );


        if($res==null)
        {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $res = curl_exec($curl);
            curl_close($curl);

        }



        return json_decode( $res );
    }
}