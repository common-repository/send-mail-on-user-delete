<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "save_smoud") {

	// if our nonce isn't there, or we can't verify it, return
    if( !isset( $_POST['save_smoud'] ) || !wp_verify_nonce( $_POST['save_smoud'], 'my_save_smoud' ) ) return;
	
    // if our current user can't edit this post, return
    if( !current_user_can( 'administrator' ) ) return;
	
	$email_subject 			= 	$_POST['email_subject'];
	$email_subject_du 		= 	$_POST['email_subject_du'];
	
	$add_new_email_txt 		= 	$_POST['add_new_email_txt'];
	$add_new_email_txt_du 	= 	$_POST['add_new_email_txt_du'];
	
	if(!empty($email_subject)){
		if(!add_option('smoud_subject', $email_subject, '', 'no')){
			update_option('smoud_subject', $email_subject);
		}
	}
	
	if(!empty($add_new_email_txt)){
		if(!add_option('add_new_email_txt', $add_new_email_txt, '', 'no')){
			update_option('add_new_email_txt', $add_new_email_txt);
		}
	}
	
	if(!empty($email_subject_du)){
		if(!add_option('smoud_subject_du', $email_subject_du, '', 'no')){
			update_option('smoud_subject_du', $email_subject_du);
		}
	}
	
	if(!empty($add_new_email_txt_du)){
		if(!add_option('add_new_email_txt_du', $add_new_email_txt_du, '', 'no')){
			update_option('add_new_email_txt_du', $add_new_email_txt_du);
		}
	}
	
	$saved = true;
	if($saved==true) {
		$message='saved';
	} 
}

if ( $message == 'saved' ) {
	echo '<div id="message" class="updated"><p><strong>Settings Saved.</strong></p></div>';
}
?>
<div class="wrap">
<h1><?php _e('SMOUD Settings','');?></h1><br/>
<form action="" method="post">
<h3><?php _e('Send Email to Admin',''); ?></h3>
<table class="form-table">
	<tbody>
		
		<tr>
			<th scope="row"><label for="email_subject"><?php _e('Email Subject', '');?></label></th>
			<td>
				<input type="text" name="email_subject" value="<?php echo get_option('smoud_subject'); ?>" class="regular-text"/>
				<p class="description"><?php _e('Default Subject: User Deleted','');?></p>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label for="email_text"><?php _e('Email Text','');?></label></th>
			<td>
			<?php 
				$email_content = '';
				$editor_id_email_txt = 'add_new_email_txt'; 
				 
				if( get_option('add_new_email_txt') != '') {
				   $email_content = stripslashes_deep(get_option('add_new_email_txt'));
				}
				 
				$settings_add_email_txt = array( 'add_new_email_txt' => 'post_text3' );
				wp_editor( $email_content, $editor_id_email_txt, $settings_add_email_txt );
			?>
			</td>
		</tr>
	</tbody>
</table><br/>

<h3><?php _e('Send Email to Deleted User',''); ?></h3>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="email_subject_du"><?php _e('Email Subject', '');?></label></th>
			<td>
				<input type="text" name="email_subject_du" value="<?php echo get_option('smoud_subject_du'); ?>" class="regular-text"/>
				<p class="description"><?php _e('Default Subject: Your Account Has Been Deleted','');?></p>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label for="email_text_du"><?php _e('Email Text','');?></label></th>
			<td>
			<?php 
				$email_content_du = '';
				$editor_id_email_txt_du = 'add_new_email_txt_du'; 
				 
				if( get_option('add_new_email_txt_du') != '') {
				   $email_content_du = stripslashes_deep(get_option('add_new_email_txt_du'));
				}
				 
				$settings_add_email_txt_du = array( 'add_new_email_txt_du' => 'post_text3_du', 'wpautop' => false, "teeny" => true );
				wp_editor( $email_content_du, $editor_id_email_txt_du, $settings_add_email_txt_du );
			?>
			</td>
		</tr>
	</tbody>
</table>
<label style="margin-left: 216px;" class="description" for="subject">Available tokens: <code>%sitename%</code>, <code>%username%</code>, <code>%useremail%</code></label>
	
<input type="hidden" name="saved"  value="saved"/>
<p class="submit"><input type="submit" name="submit" value="Save Changes" class="button button-primary"/></p>
<input type="hidden" name="action" value="save_smoud" />
<?php wp_nonce_field( 'my_save_smoud', 'save_smoud' ); ?>
</form>
</div>