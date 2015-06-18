<?php 
wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox'); 
wp_enqueue_script('media-upload');
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-dialog');

global $insights_leadsius_forms,$insights_widget_icons,$select_colors;
$badges = array( "Free", "Hot", "New", "Top" );
$styles = array( "Naked", "Box-Blank", "Box-Line", "Box-Color" );

?>
<?php 
?>
<style>
	
	.clearfix:before,
	.clearfix:after {
		content:"";
		display:table;
	}
	.clearfix:after {
		clear:both;
	}
	.clearfix {
		zoom:1; /* For IE 6/7 (trigger hasLayout) */
	}
	.picker {

		width:70px;
		
		
		
	}


	.select_colors{

		display: none;
	}

	header{

		background: white;

		margin-bottom: 20px;
	}

	header span{

		font-weight: bold;
		margin-bottom: 20px;
		margin-top: 20px;
	}
	.form-row{

		width: inherit;
		margin: 10px 0;
		line-height: 26px;
		background: #FFF;
		padding: 12px;
		border: 1px solid #E5E5E5;
		-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
	}


	.edit_this_form{

		
		width: 35%;
		margin-top: 46px;
		border: 1px dashed #D3D3D3;
		padding: 30px;
		margin-left: 15px;
		margin-bottom: 20px;
	}
	.col1{


		width: 35%;
		display: block;
		float: left;

	}

	.col2{


		width: 25%;
		display: block;
		float: left;

	}


	.col3{

		width: 20%;
		display: block;
		float: left;
	}
	.cont_picker{

		display: none;
	}
	.color_square{

		width: 30px;
		height: 30px;
		display: inline-block;
		vertical-align: bottom;
	}

	.new_form{

		display: none;
		width: 30%;

	}


	.new_webform{

		margin-top: 60px;
	}

	.main_container{


		padding-right: 20px;
	}

	.cont_new_button{


		margin-top: 50px;
	}

</style>
<div class="main_container">
	<h2>CTA Settings</h2>




	<div class="forms_table widefat mp6-primary wp-list-table fixed posts">

		<header>
			<span class="col1">Name</span><span class="col2">Shortcode</span><span class="col3">Type</span>
		</header>
		<section>
			<?php
			if(get_option('widget_widget_cta_insights')){
				?>
				<?php foreach (get_option('widget_widget_cta_insights') as $key=>$form): ?>

					<?php if($key!='_multiwidget'): ?>	
						<?php $id='widget_cta_insights-'.$key; ?>
						<div id="form-<?php echo $key;?>" class="clearfix form-row widefat">
							<span class="col1  column-title"><?php if(isset($form['name'])&& ($form['name']!='')){echo $form['name'];}else{echo 'widget-'.$key;} ?></span>
							<?php 
							if($form['category']=="shortcode"){

								?>
								<span class="col2 column-shortcode">
									[leadsius-cta id="<?php echo $key; ?>"]</span> 
									<?php 	

								}else{
									?>
									<span class="col2 column-shortcode" style="text-indent: -99999px;">null</span> 

									<?php }?>
									<span class="col3"><?php echo $form['category']; ?></span>
									<div class="col4">
										<?php if($form['category']=="shortcode"){?>
										<button class="edit button">Edit</button><button class="copy button">Copy</button><button class="delete button">Delete</button><span class="spinner"></span>

										<?php }else{?>

										<?php get_sidebar_parent($id); ?>


										<?php }?>
									</div>

									<div class="edit_this_form" style="display:none"></div>
								</div>



							<?php endif; ?>		
						<?php endforeach; ?>
						<?php } ?>
					</section>
				</div>







				<div class="cont_new_button"><button class="new_webform button-primary">Add new shortcode CTA</button></div>
				<section class="new_form" id="new_form">
					<form>


						<div class="widget-config">

							<p>
								<label><?php _e('Name', 'insights'); ?></label>
								<textarea class="widefat" name="name"></textarea>
							</p>



							<p style="display:none">
								<label><?php _e('Title', 'insights'); ?></label>
								<textarea class="widefat" name="title"></textarea>
							</p>
							<p style="display:none">
								<label><?php _e('Body', 'insights'); ?></label>
								<textarea class="widefat" name="body"></textarea>
							</p>
							<p>
								<label><?php _e('Button text', 'insights'); ?></label>
								<input type="text" class="widefat" name="button-text" value="" />
							</p>
							<p>
								<label><?php _e('Button URL', 'insights'); ?></label>
								<input type="text" class="widefat" name="button-url" value="" />
								<a href="#" class="set-link-button">Set Link</a>
							</p>
							<p>
								<input type="hidden" name="image" value="" />


							</p>

							<p style="display:none">
								<label><?php _e('Style', 'insights'); ?></label>
								<select class="widefat" name="style">

									<?php foreach ($styles as $style): ?>
										<option value="<?php echo $style; ?>"><?php echo $style; ?></option>
									<?php endforeach; ?>


								</select>
							</p>

							<p style="display:none" class="select_colors">
								<label><?php _e('Colors', 'insights'); ?></label>
								<select class="widefat" name="color">
									<option value="none">-- none --</option>
									<?php foreach ($select_colors as $color): ?>
										<option value="<?php echo $color; ?>"><?php echo $color; ?></option>
									<?php endforeach; ?>


								</select>
							</p>
							<!--<p>
								<label><?php _e('Button icon', 'insights'); ?></label>
								<select class="widefat" name="icon">
									<option value="">-- none --</option>
									<?php foreach($insights_widget_icons as $key => $value): ?>
										<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php endforeach; ?>
								</select>
							</p>
							<p style="display:none">
								<label><?php _e('Badge', 'insights'); ?></label>
								<select class="widefat" name="badge">
									<option value="">-- none --</option>
									<?php foreach($badges as $value): ?>
										<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
									<?php endforeach; ?>
								</select>
							</p> -->

			<!--<p>
				<label><?php _e('Where to show', 'insights'); ?></label>
				<?php where_to_show_selector('pages',''); ?>
			</p> -->
			<p>

				<button class="save_new_form">Save</button>
				<a href="#" class="close_new_form">Close</a>
				<span class="spinner"></span>

			</p>
		</div>




	</form>
</section>


</div>




<!--- 






-->


<script id="form-edit-template" type="text/x-handlebars-template">

	<form>


		<div class="widget-config">

			<p>
				<label>Name</label>
				<textarea class="widefat" name="name">{{0.request.name}}</textarea>
			</p>

			<p style="display:none">
				<label>Title</label>
				<textarea class="widefat" name="title">{{0.request.title}}</textarea>
			</p>
			<p style="display:none">
				<label>Body</label>
				<textarea class="widefat" name="body">{{0.request.body}}</textarea>
			</p>

			<p>
				<label>Button text</label>
				<input type="text" class="widefat" name="button-text" value="{{0.request.button-text}}" />
			</p>
			<p>
				<label>Button URL</label>
				<input type="text" class="widefat" name="button-url" value="{{0.request.button-url}}" />
				<a href="#" class="set-link-button">Set Link</a>
			</p>
			<p>
				<input type="hidden" name="image" value="{{0.request.image}}" />
				
			</p>

			
			<p style="display:none">
				<label>Style</label>
				<select class="widefat" name="style">

					{{#each 1.styles}}
					<option value="{{this.val}}" {{#is this.selected "selected"}} selected="selected" {{/is}}>{{this.val}}</option>
					{{/each}}
				</select>
			</p>
			
			<p class="select_colors" style="display:none">
				<label>Colors</label>
				<select class="widefat" name="color">
					<option value="none">-- none --</option>
					{{#each 1.color}}
					<option value="{{this.val}}" {{#is this.selected "selected"}} selected="selected" {{/is}}>{{this.val}}</option>
					{{/each}}


				</select>
			</p>
			




			
			<p>

				<button class="update_form">Update</button>
				<a href="#" class="cancel_update_form">Cancel</a>
				<span class="spinner"></span>
			</p>
		</div>



	</form>

</script>


<script id="form-row-template" type="text/x-handlebars-template">


	<div id="form-{{id}}" class="clearfix form-row widefat">
		<span class="col1 column-title">{{name}}</span>
		<span class="col2 column-shortcode">[leadsius-cta id="{{id}}"]</span>
		<div class="col3">
			<button class="edit button">Edit</button><button class="copy button">Copy</button><button class="delete button">Delete</button><span class="spinner"></span>
		</div>

		<div class="edit_this_form" style="display:none"></div>
	</div>

</script>

<?php 




add_action( 'admin_footer', 'web_form_settings_ajax' );

function web_form_settings_ajax() {
	//$nonce = wp_create_nonce( 'helloworld' );
	//
	//
	/* Use Link Window In Widgets Page */

	/*$pages = get_pages();
    $posts=get_posts();
	$ulPages = "";

	$index = 0;
    $index2 = 0;
	foreach($pages as $page)
	{
		$ulPages .= sprintf('<li class="%s"><input type="hidden" value="%s" class="item-permalink" /><span class="item-title">%s</span><span class="item-info">Page</span></li>'
			, ($index++ % 2 ? 'alternate' : ''), get_permalink($page), $page->post_title);
	}
    foreach($posts as $post)
    {
        $ulPages .= sprintf('<li class="%s"><input type="hidden" value="%s" class="item-permalink" /><span class="item-title">%s</span><span class="item-info">Post</span></li>'
            , ($index2++ % 2 ? 'alternate' : ''), get_permalink($post), $post->post_title);
    }

	wp_editor('', 'tool-editor');*/
	?>





	<script  type='text/javascript'>




		/***  start common new form settings  ***/



		var imageSourceField;

		(function($) {


/************************************
****************************/















/***************************
************************************/




//$(".tabbed").tabber();

$(document).ready(function(){




	$(document).on("click",".scwu",function(e){


		e.preventDefault();
		var parent=$(this).parent().parent().parent().parent().parent().attr('id');
		console.log(parent);

		imageSourceField=$('#'+parent).find('.scwu').siblings('input');
		tb_show("","media-upload.php?TB_iframe=true");


		window.send_to_editor = function(html) {
			if (imageSourceField)
			{
				var url = $("img", html).attr("src");
				imageSourceField.val(url);
				imageSourceField.parent().append($("<img/>", { "src": url }).css('max-width', 150).css('display', 'block').css('margin', '5px 0px'));
				imageSourceField.parent().append($("<span/>", { "class": 'remove-image', 'text': 'Remove Image' }).css('cursor', 'pointer').css('color', '#ff0000'));

				imageSourceField = null;
			}
			tb_remove();
		};
	});

});


$(document).on("click",".remove-image",function(e){

	e.preventDefault();
	var parent=$(this).parent().parent().parent().parent().parent().attr('id');
	console.log(parent);
	if (confirm('Are you sure?'))
	{
		$('#'+parent+' .remove-image').siblings("input").val("");
		$('#'+parent+' .remove-image').siblings("img").remove();
		$('#'+parent+' .remove-image').remove();
	}

});

$(document).on('click', '.set-link-button', function(event) {
	event.preventDefault();
	/* Act on the event */

	window.ShowLinkWindow($(this));

});



/*** end common new form settings***/ 


})(jQuery);


jQuery(document).ready(function($){


	

	/*** change style . if style=='Box-Color'  show colors dropdown****/

	$(document).on('change', 'select[name="style"]', function(event) {
		event.preventDefault();
		/* Act on the event */
		var parent;
		if($(this).parent().parent().parent().parent().hasClass('new_form')){

			console.log(true);
			parent=$(this).parent().parent().parent().parent().attr('id');
		}
		else{

			parent=$(this).parent().parent().parent().parent().parent().attr('id');
		}

		if($('#'+parent).find('select[name="style"] option:selected').val()=="Box-Color"){

			$('#'+parent).find('.select_colors').show();

		}else{

			$('#'+parent).find('.select_colors').hide();
			$('#'+parent).find('.select_colors select option[value="none"]').attr('selected','selected');
		}

	});

	/*** display fields to create new form ****/

	jQuery( document ).on( "click", ".new_webform", function() {


		$('.new_form').show();


	});


	/****  close new form ****/
	$(document).on('click','.close_new_form',function(e){

		e.preventDefault();
		$('.new_form').fadeOut('slow');
	});

	/****  create new form ****/

	jQuery( document ).on( "click", ".save_new_form", function(e) {

		e.preventDefault();

		$el=$(this);
		var ser=$('.new_form').find('form').serializeArray();


		jQuery.ajax({
			type: "post",
			url: ajaxurl,
			data: {
				action: 'cta_settings_save_new',
				datos: ser
			},
			beforeSend: function() {

				$('.new_form').find('.spinner').show();
				$el.attr('disabled','disabled');
			}, 
			complete: function(res) {

				$('.new_form').find('.spinner').hide();
				$el.removeAttr('disabled');
			}, 
			success: function(res){ 

				console.log(res);
				var json=$.parseJSON(res);
				var source = $("#form-row-template").html(); 
				var template = Handlebars.compile(source); 

				console.log(json);
				$('.forms_table section').append(template(json));

				$('.new_form').fadeOut();
			}
		}); 


	});


	/****   delete form******/



	$(document).on("click",".delete",function(event) {

		$button=$(this);

		var parent=$(this).parent().parent().attr('id');

		var id=parent.split('-');
		var par=$.parseHTML('#'+parent);
		jQuery.ajax({
			type: "post",
			url: ajaxurl,
			data: {
				action: 'cta_settings_delete',
				id_form: id[1]
			},
			beforeSend: function() {

				$(par[0].data).find('.spinner').show();
				$button.attr('disabled', 'disabled');
			}, 
			complete: function(res) {


			}, 
			success: function(res){ 




				console.log(res);
				if(res=='deleted'){

					$(par[0].data).remove();
				}


			}
		}); 

	});





	/*******  edit form  ************/

	$(document).on("click",".edit",function(event) {
		$button=$(this);
		var parent=$(this).parent().parent().attr('id');

		var id=parent.split('-');
		var par=$.parseHTML('#'+parent);
		jQuery.ajax({
			type: "post",
			url: ajaxurl,
			data: {
				action: 'cta_settings_edit',
				id_form: id[1]
			},
			beforeSend: function() {

				$(par[0].data).find('.spinner').show();
				$button.attr('disabled', 'disabled');
			}, 
			complete: function(res) {

				$(par[0].data).find('.spinner').hide();
			}, 
			success: function(res){ 




				console.log(res);

				var json=$.parseJSON(res);
				var source = $("#form-edit-template").html(); 
				var template = Handlebars.compile(source); 

				console.log(json);
				$(par[0].data).find('.edit_this_form').show().append(template(json));

			}
		}); 


	});



	/**** cancel update*******/


	$(document).on('click','.cancel_update_form',function(e){

		e.preventDefault();
		var parent=$(this).parent().parent().parent().parent().parent().attr('id');
		var par=$.parseHTML('#'+parent);
		$(par[0].data).find('.edit_this_form').fadeOut('slow',function(){


			$(par[0].data).find('.edit_this_form').html('');
		});
		$(par[0].data).find('.edit').removeAttr('disabled');
	});



	/*****   update  form***********/



	$(document).on("click",".update_form",function(event) {

		$button=$(this);
		event.preventDefault();
		var parent=$(this).parent().parent().parent().parent().parent().attr('id');
		console.log(parent);
		var id=parent.split('-');
		var par=$.parseHTML('#'+parent);

		var ser=$(par[0].data).find('form').serializeArray();
		jQuery.ajax({
			type: "post",
			url: ajaxurl,
			data: {
				action: 'cta_settings_update',
				id_form: id[1],
				datos:ser
			},
			beforeSend: function() {
				$(par[0].data).find('.spinner').show();
				$button.attr('disabled', 'disabled');
			}, 
			complete: function(res) {
				$(par[0].data).find('.spinner').hide();
				$(par[0].data).find('.edit_this_form').fadeOut('slow',function(){

					$(par[0].data).find('.edit_this_form').html('');
				});
				$(par[0].data).find('.edit').removeAttr('disabled');				

			}, 
			success: function(res){ 




				console.log(res);

				var json=$.parseJSON(res);

				$(par[0].data).find('.column-title').text(json.name);

			}
		}); 


	});



	/*****   copy  form***********/



	$(document).on("click",".copy",function(event) {

		$button=$(this);
		event.preventDefault();
		var parent=$(this).parent().parent().attr('id');
		console.log(parent);
		var id=parent.split('-');

		var par=$.parseHTML('#'+parent);
		
		jQuery.ajax({
			type: "post",
			url: ajaxurl,
			data: {
				action: 'cta_settings_copy',
				id_form: id[1]
			},
			beforeSend: function() {
				$(par[0].data).find('.spinner').show();
				$button.attr('disabled', 'disabled');
			}, 
			complete: function(res) {
				$(par[0].data).find('.spinner').hide();
				$(par[0].data).find('.edit_this_form').fadeOut('slow',function(){

					$(par[0].data).find('.edit_this_form').html('');
				});
				$(par[0].data).find('.edit').removeAttr('disabled');				

			}, 
			success: function(res){ 




				console.log(res);

				var json=$.parseJSON(res);
				var source = $("#form-row-template").html(); 
				var template = Handlebars.compile(source); 

				console.log(json);
				$('.forms_table section').append(template(json));
				$button.removeAttr('disabled');
			}
		}); 


	});



});














-->
</script>



<?php
}



?>

