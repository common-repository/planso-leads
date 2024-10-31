<?php
/**
 * Plugin Name: PlanSo Leads
 * Plugin URI: http://leads.planso.de/
 * Description: Lead generation simple as 1-2-3. PlanSo Leads allows you to display different types of lead teasers managed within PlanSo Leads. No matter if you are looking for modals, sticky bars, slide-ins, page takeovers call-outs or sticky lashes: PlanSo Leads has beautyful assets for you to use in minutes.
 * Version: 1.0.5
 * Author: PlanSo.de
 * Author URI: http://leads.planso.de/
 * Text Domain: psl
 * Domain Path: /locale/
 * License: GPL2
 */
/*  Copyright 2015  Stephan Helbig  (email : tech@planso.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

load_plugin_textdomain( 'psl', false, dirname( plugin_basename( __FILE__ ) ).'/locale' );

add_action('wp_footer', 'psl_enqueue_script');
if(!function_exists('psl_enqueue_script')){
	function psl_enqueue_script(){
		$token = get_option('psl_token',false);
		if($token!=false){
			wp_register_script( 'planso-leads','//'.$token.'.planso.de/js/psl/'.sha1($token).'.js', array(), '1.0.0', true );
			wp_enqueue_script( 'planso-leads' );
		}
	}
}


if(!function_exists('psl_admin_menu')){
	/** Register Admin Menu */
	add_action( 'admin_menu', 'psl_admin_menu' );
	
	/** Hook Admin Menu */
	function psl_admin_menu() {
		/*
		add_menu_page( 
			$page_title, 
			$menu_title, 
			$capability, 
			$menu_slug, 
			$function, 
			$icon_url,
			$position 
		);
		*/
		$edit = add_menu_page( 
			__('PlanSo Leads','psl'), 
			__('PlanSo Leads','psl'), 
			'manage_options', 
			'psl', 
			'psl_options',
			plugins_url( '/images/planso-logo-gears-transparent-20x20.png', (__FILE__) ),
			'65.88255991543'
		);
		do_action( 'psl_admin_menu' );
		/*
		add_action( 'load-' . $edit, 'psl_load_contact_form_admin' );
		*/
	}
}


if(!function_exists('psl_options')){
	function psl_options() {
	
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		
		wp_register_style( 'font-awesome',plugins_url( '/css/font-awesome-4.3.0/css/font-awesome.min.css', (__FILE__) ) ,array() ,'4.3.0');
		wp_enqueue_style( 'font-awesome');
		wp_enqueue_style( 'bootstrap',plugins_url( '/css/bootstrap/full/bootstrap.min.css', (__FILE__) ) );
		wp_enqueue_style( 'bootstrap-theme',plugins_url( '/css/bootstrap/full/bootstrap-theme.min.css', (__FILE__) ) );
		
	
		wp_register_script( 'bootstrap-tooltip',plugins_url( '/js/bootstrap/src/tooltip.js', (__FILE__) ), array('jquery'), '3.2.2', true );
		wp_register_script( 'bootstrap-modal',plugins_url( '/js/bootstrap/src/modal.js', (__FILE__) ), array('jquery'), '3.2.2', true );
		wp_register_script( 'bootstrap-collapse',plugins_url( '/js/bootstrap/src/collapse.js', (__FILE__) ), array('jquery'), '3.2.2', true );
		wp_register_script( 'bootstrap-popover',plugins_url( '/js/bootstrap/src/popover.js', (__FILE__) ), array('jquery'), '3.2.2', true );
		wp_register_script( 'bootstrap-tab',plugins_url( '/js/bootstrap/src/tab.js', (__FILE__) ), array('jquery'), '3.2.2', true );
		wp_register_script( 'bootstrap-transition',plugins_url( '/js/bootstrap/src/transition.js', (__FILE__) ), array('jquery'), '3.2.2', true );
		wp_register_script( 'bootstrap-dropdown',plugins_url( '/js/bootstrap/src/dropdown.js', (__FILE__) ), array('jquery'), '3.2.2', true );
		
		wp_enqueue_script( 'bootstrap-tooltip' );
		wp_enqueue_script( 'bootstrap-modal' );
		wp_enqueue_script( 'bootstrap-collapse' );
		wp_enqueue_script( 'bootstrap-popover' );
		wp_enqueue_script( 'bootstrap-tab' );
		wp_enqueue_script( 'bootstrap-transition' );
		wp_enqueue_script( 'bootstrap-dropdown' );
		
		$reg_show = 'true';
		$reg_show_cls = 'in';
		$reg_show_a_cls = '';
		$opt_show = 'false';
		$opt_show_cls = '';
		$opt_show_a_cls = 'collapsed';
		
		$token = get_option('psl_token','');
		
		if(trim($token) != ''){
			$reg_show = 'false';
			$opt_show = 'true';
			$opt_show_cls = 'in';
			$reg_show_cls = '';
			$opt_show_a_cls = '';
			$reg_show_a_cls = 'collapsed';
		}
		
		$current_user = wp_get_current_user();
		
		
		
		?>
		<div class="wrap">
<div style="float:right;">
	<a href="https://wordpress.org/support/view/plugin-reviews/planso-leads?rate=5#postform" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-heart"></i> <?php echo __('Like PlanSo Leads? Post a review!','psl'); ?></a>
</div>
<h2><?php
	
		echo esc_html( __( 'PlanSo Leads', 'psl' ) );
		
?></h2>

<?php do_action( 'psl_admin_notices' ); ?>

<br class="clear" />


<script type="text/javascript">


jQuery(document).ready(function($){

	
	$('button.planso_close_warning').click(function(){
		$('#planso_warning_save').modal('hide');
	});
	
	$('.psl_update_option').click(function(){
		var data = {
			'action': 'psl_update_option',
			'token': $('#planso_option_token').val()
		};
		$.ajax({
			url:ajaxurl,
			data:data,
			dataType:'json',
			type:'post',
			success: function(r) {
				if(r.success==1 || r.success=='1'){
					if( $('#collapseOne').hasClass('in') ){
						$('#collapseOne').collapse('hide');
					}
					if( $('#collapseTwo').hasClass('in') ){
						
					} else {
						$('#collapseTwo').collapse('show');
					}
					$('#planso_option_token').closest('.form-group').removeClass('has-error').addClass('has-success');
				} else {
					$('#planso_option_token').closest('.form-group').removeClass('has-success').addClass('has-error');
				}
			}
		});
	});
	
	$('#planso_register_email').change(function(){
		var e = $(this).val();
		if(e.indexOf('@')!=-1){
			var ee = e.split('@');
			var eee = ee[1].split('.');
			var token = eee[0];
			$('#planso_register_token').val(token).trigger('change').closest('.form-group').show();
		}
	});
	var register_token_check = null;
	$('#planso_register_token').change(function(){
		
		var dat = {token:$(this).val()};
		$.ajax({
			url:'//planso.de/public.registration.check.token.php',
			type:'post',
			dataType:'json',
			data:dat,
			success:function(r){
				if(typeof r.identifier != 'undefined' ){
					if(r.available==1){
						$('#planso_register_token').closest('.form-group').removeClass('has-error').addClass('has-success');
						$('.token_status_icon').removeClass('fa-close').addClass('fa-check');
					} else {
						$('#planso_register_token').focus().closest('.form-group').removeClass('has-success').addClass('has-error');
						$('.token_status_icon').removeClass('fa-check').addClass('fa-close');
					}
					$('#planso_register_token').val(r.identifier);
				}
			}
		});
	}).keyup(function(){
		window.clearTimeout(register_token_check);
		register_token_check = window.setTimeout(function(){
			$('#planso_register_token').trigger('change');
		},850);
	});
	
	
	$('.planso_perform_registration').click(function(){
		$(this).prop('disabled',true);
		$('.planso_register_modal .has-error').removeClass('has-error');
		$('.planso_register_modal_error_info').html('');
		
		var myUrl = window.location.href;
		if(myUrl.indexOf('planso.de')!=-1 ){
			var loc = 'de_DE';
		} else {
			var loc = 'en_US';
		}
		var dat = {company: {
				firstname: $('#planso_register_firstname').val(),
				lastname: $('#planso_register_lastname').val(),
				company: $('#planso_register_company').val(),
				email: $('#planso_register_email').val(),
				url: $('#planso_register_url').val(),
				token: $('#planso_register_token').val()
			},
			locale: loc,
			json:$('#psfb_json').val() 
		};
		$.ajax({
			url:'//planso.de/public.registration.psl.php',
			type:'post',
			dataType:'json',
			data:dat,
			success:function(r){
				$('.planso_perform_registration').prop('disabled',false);
				if(typeof r.token != 'undefined' && r.token.length>0 && typeof r.cID != 'undefined' && r.cID > 0 ){
					$('#planso_option_token').val(r.token);
					$('.psl_update_option').trigger('click');
					
					$('#planso_thank_you').modal('show');
					$('.planso_continue_to_login').attr('href','http://'+r.token+'.planso.de/app/'+loc);
					$('.planso_continue_to_login').click(function(){
						//cookie setzen unm erneutes registrieren zu verhindern
					});
					
				} else {
					
					$('.planso_register_modal .has-error').removeClass('has-error');
					if(typeof r.error != 'undefined' && r.error.length>0){
						if(typeof r.message != 'undefined'){
							$('.planso_register_modal_error_info').html('<p class="alert alert-danger">'+r.message+'</p>');
						}
						$.each(r.error,function(i){
							$('#planso_register_'+ r.error[i]).addClass('has-error').closest('.form-group').addClass('has-error');
						});
						
					} else if(typeof r.message != 'undefined'){
						$('.planso_register_modal_error_info').html('<p class="alert alert-danger">'+r.message+'</p>');
					}
				}
				
			},
			error:function(o,r){
				console.log('Error');
				console.log(o);
				console.log(r);
				$('.planso_perform_registration').prop('disabled',false);
			}
		});
	});
	
	
	
	
	
});





</script>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a class="<?php echo $reg_show_a_cls; ?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="<?php echo $reg_show; ?>" aria-controls="collapseOne">
          I am new to PlanSo and need a new token
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse <?php echo $reg_show_cls; ?>" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body planso_register_modal">
        
       		 <div class="planso_register_modal_error_info"></div>
      	
		      	<div class="row">
						  <div class="form-group col-md-6">
						    <label for="planso_register_firstname"><?php echo __('First name','psl'); ?>*</label>
						    <div class="input-group">
						    	<div class="input-group-addon"><span class="fa fa-user"></span></div>
						    	<input type="text" id="planso_register_firstname" class="form-control" value="<?php echo $current_user->user_firstname; ?>">
						    </div>
						    <!--
						    <p class="help-block"><?php echo __('Please enter your first name.','psl'); ?></p>
						    -->
						  </div>
			      	
						  <div class="form-group col-md-6">
						    <label for="planso_register_lastname"><?php echo __('Last name','psl'); ?>*</label>
						    <div class="input-group">
						    	<div class="input-group-addon"><span class="fa fa-user"></span></div>
						    	<input type="text" id="planso_register_lastname" class="form-control" value="<?php echo $current_user->user_lastname; ?>">
						    </div>
						    <!--
						    <p class="help-block"><?php echo __('Please enter your last name.','psl'); ?></p>
					    	-->
						  </div>
					  </div>
					  <div class="row">
						  <div class="form-group col-md-6">
						    <label for="planso_register_email"><?php echo __('E-Mail','psl'); ?>*</label>
						    <div class="input-group">
						    	<div class="input-group-addon"><span class="fa fa-at"></span></div>
						    	<input type="text" id="planso_register_email" class="form-control" value="<?php echo $current_user->user_email; ?>">
						    </div>
						    <!--
						    <p class="help-block"><?php echo __('Please enter your first name.','psl'); ?></p>
					    	-->
						  </div>
						  
						  <div class="form-group col-md-6">
						    <label for="planso_register_company"><?php echo __('Company','psl'); ?></label>
						    <div class="input-group">
						    	<div class="input-group-addon"><span class="fa fa-institution"></span></div>
						    	<input type="text" id="planso_register_company" class="form-control">
						    </div>
						    <!--
						    <p class="help-block"><?php echo __('Please enter the name of your company. This will be used as your login token.','psl'); ?></p>
					   		-->
						  </div>
			      	
			      	
					  </div>
					  <div class="row">
						  <div class="form-group col-md-12">
						    <label for="planso_register_url"><?php echo __('Website-Address','psl'); ?>*</label>
						    <div class="input-group">
						    	<div class="input-group-addon"><span class="fa fa-link"></span></div>
						    	<input type="url" id="planso_register_url" class="form-control" placeholder="http://yourdomain.com/" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
						    </div>
						    <!--
						    <p class="help-block"><?php echo __('Please enter your first name.','psl'); ?></p>
					    	-->
						  </div>
			      	
					  </div>
					  <div class="row">
						  <div class="form-group col-md-12">
						    <label for="planso_register_token"><?php echo __('Token','psl'); ?>*<br/><small>(<?php echo __('Min 3 lowercase characters from a to z','psl'); ?>)</small></label>
						    <div class="input-group">
						    	<div class="input-group-addon"><span class="token_status_icon fa fa-close"></span></div>
						    	<input type="text" id="planso_register_token" class="form-control">
						    	<div class="input-group-addon"><span>.planso.de</span></div>
						    </div>
						    <p class="help-block"><?php echo __('This is the subdomain your instance will be installed to.','psl'); ?></p>
						  </div>
		      	</div>
					  <div class="form-group">
					    <p class="help-block"><?php echo __('Clicking the button below will submit the information entered above to PlanSo.de. A new instance of PlanSo Leads will then be installed for you and you will receive an email with your administrator credentials. You will <b>not</b> be billed anything and <b>no credit card is required</b> until you decide to upgrade to a paid plan. For the details of the different plans please refere to our website at:','psl'); ?> <a href="http://www.planso.net/planso-leads/" target="_blank">PlanSo Leads</a></p>
					  </div>
					  <div class="row">
						  <div class="form-group col-md-12">
					  		<button type="button" class="btn btn-primary planso_perform_registration"><?php echo __('Obtain free token','psl'); ?></button>
    				 </div>
						</div>
        
        
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="<?php echo $opt_show_a_cls; ?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="<?php echo $opt_show; ?>" aria-controls="collapseTwo">
          I already have a token and want to use it
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse <?php echo $opt_show_cls; ?>" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
        <div class="row">
	        <div class="form-group col-md-4">
				    <label for="planso_option_token"><?php echo __('Company Token','psl'); ?>*</label>
				    <div class="input-group">
				    	<div class="input-group-addon">http://</div>
				    	<input type="text" id="planso_option_token" class="form-control" value="<?php echo $token; ?>">
				    	<div class="input-group-addon"><span>.planso.de</span></div>
				    </div>
				    <p class="help-block"><?php echo __('The company token is the word found in front of planso.de as in TOKEN.planso.de when logging into your instance.','psl'); ?></p>
				  </div>
	        
	        <div class="form-group col-md-8">
	        	
	        </div>
	      </div>
        <div class="row">
	        <div class="form-group col-md-12">
	        	<button type="button" class="btn btn-primary psl_update_option"><?php echo __('Update','psl'); ?></button>
				  </div>
				</div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="planso_thank_you" tabindex="-1" role="dialog" aria-labelledby="planso_thank_you_modal_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="planso_thank_you_modal_label"><?php echo __('Thank you for signing up','psl'); ?></h4>
      </div>
      <div class="modal-body">
      	<h3>
      		<span class="fa fa-check" style="color:#1A8B49;"></span>
      		<?php echo __('Your PlanSo account is ready!','psl'); ?>
      	</h3>
      	<p><?php echo __('In short you will receive an email containig your username and password as well as the login url.','psl'); ?></p>
      	<p><?php echo __('As soon as you login for the first time you will be able to create your lead teasers.','psl'); ?></p>
      	<p><?php echo __('In case you have any questions regarding PlanSo do not hesitate to contact us via support@planso.de!','psl'); ?></p>
      	
      	
      </div>
      <div class="modal-footer">
        <a href="http://leads.planso.de/" class="btn btn-primary planso_continue_to_login"><?php echo __('Continue to login','psl'); ?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div style="clear:both;"></div>
</div><!-- wrap -->
<div style="clear:both;"></div>
		<?php
	}
}

add_action( 'wp_ajax_psl_update_option', 'psl_update_option' );

function psl_update_option() {
	global $wpdb; // this is how you get access to the database
	if(isset($_POST['token']) && strlen(trim($_POST['token']))>2){
		update_option('psl_token',$_POST['token']);
		echo json_encode(array('success'=>1));
	} else {
		echo json_encode(array('success'=>0));
	}
	wp_die();
}
?>