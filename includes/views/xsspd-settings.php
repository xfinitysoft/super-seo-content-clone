<?php 
// Get All registered Post Types on site
$post_types = get_post_types();

// removing the post types which we dont need 
unset ( $post_types['attachment'] );
unset ( $post_types['revision'] );
unset ( $post_types['nav_menu_item'] );
unset ( $post_types['custom_css'] );
unset ( $post_types['customize_changeset'] );
unset ( $post_types['oembed_cache'] );
unset ( $post_types['product_variation'] );
unset ( $post_types['shop_coupon'] );
unset ( $post_types['shop_order_refund'] );
global $wp_roles;
$all_roles = $wp_roles->roles;

?>
<div class="wrap">
	<h1>
		<?php esc_html_e('General Settings', 'super-seo-content-cloner'); ?>
		<a class="xsspd-pro-link" href="https://codecanyon.net/item/smart-page-duplicator-duplicate-contents-through-find-and-replace/22075205" target="_blank">
			<div class="xsspd-button-main">
				<?php submit_button(esc_html__("Pro Version" , 'super-seo-content-cloner' ), 'secondary' , "xsspd-button"); ?>
			</div>
        </a>	
	<h1>
	<form method="post" action="">
		<table class="form-table">
			<tbody>
				<tr>
					<th>
						<?php esc_html_e('Allowed Roles', 'super-seo-content-cloner'); ?>
						<p class="xsspd_desc"><?php esc_html_e('Users belong to these roles will see Duplicate Post Link.', 'super-seo-content-cloner'); ?></p>
					</th>
					<td>
						<?php foreach($all_roles as $key=>$role){ ?>
							<label style="display:block;text-transform: uppercase;margin-bottom: 2px"><input type="checkbox" name="allowed_roles[]" value="<?php echo esc_attr($key); ?>" <?php if(in_array($key, $this->allowed_roles)) echo esc_attr('checked'); ?> /><?php echo esc_html($role['name']); ?></label>
						<?php } ?>
					</td>
				</tr>
				
				<tr>
					<th>
						<?php esc_html_e('Enable for Post types', 'super-seo-content-cloner'); ?>
						<p class="xsspd_desc"><?php esc_html_e('Duplicate button will be shown for this type of posts.', 'super-seo-content-cloner'); ?></p>
					</th>
					<td>
						<?php foreach($post_types as $key=>$post_type){ ?>
							<label style="display:block;text-transform: uppercase;margin-bottom: 2px"><input type="checkbox" name="enabled_post_types[]" value="<?php echo esc_attr($key); ?>" <?php if(in_array($key, $this->enabled_post_types)) echo esc_attr('checked'); ?> /><?php echo esc_html(str_replace('_', ' ', $post_type)); ?></label>
						<?php } ?>
					</td>
				</tr>
				
				<tr>
					<th>
						<?php esc_html_e('Duplicate Page/Post Status', 'super-seo-content-cloner'); ?>
						<p class="xsspd_desc"><?php esc_html_e('Duplicated post status will be same as orignal or draft.', 'super-seo-content-cloner'); ?></p>
					</th>
					<td>
						<select name="new_status">
							<option value="same_as_orignal" <?php if($this->new_post_status == 'same_as_orignal') echo 'selected'; ?> ><?php esc_html_e('Same as Orignal', 'super-seo-content-cloner'); ?></option>
							<option value="publish" <?php if($this->new_post_status == 'publish') echo 'selected'; ?> ><?php esc_html_e('Publish', 'super-seo-content-cloner'); ?></option>
							<option value="pending" <?php if($this->new_post_status == 'pending') echo 'selected'; ?> ><?php esc_html_e('Pending', 'super-seo-content-cloner'); ?></option>
							<option value="private" <?php if($this->new_post_status == 'private') echo 'selected'; ?> ><?php esc_html_e('rivate', 'super-seo-content-cloner'); ?></option>
							<option value="draft" <?php if($this->new_post_status == 'draft') echo 'selected'; ?> ><?php esc_html_e('Draft', 'super-seo-content-cloner'); ?></option>
						</select>
					</td>
				</tr>
				
				<tr>
					<th>
						<?php esc_html_e('Redirect to', 'super-seo-content-cloner'); ?>
						<p class="xsspd_desc"><?php esc_html_e('Checked this box if you want to redirect to newly duplicated post when it is duplicated', 'super-seo-content-cloner'); ?></p>
					</th>
					<td>
						<select name="redirect_to_duplicated_post">
							<option value="0" <?php if($this->redirect_to_duplicated_post == '0') echo 'selected'; ?> ><?php esc_html_e('Do not Redirect', 'super-seo-content-cloner'); ?></option>
							<option value="front" <?php if($this->redirect_to_duplicated_post == 'front') echo 'selected'; ?> ><?php esc_html_e('Duplicated Post Front Side', 'super-seo-content-cloner'); ?></option>
							<option value="admin" <?php if($this->redirect_to_duplicated_post == 'admin') echo 'selected'; ?> ><?php esc_html_e('Duplicated Post Admin Side', 'super-seo-content-cloner'); ?></option>
						</select>
					</td>
				</tr>
				
				<tr>
					<th>
						<?php esc_html_e('Duplicate Link Text', 'super-seo-content-cloner'); ?>
						<p class="xsspd_desc"><?php esc_html_e('Text of the Duplicate link or Duplicate Button', 'super-seo-content-cloner'); ?></p>
					</th>
					<td>
						<input type="text" value="<?php echo esc_attr($this->duplicate_link_text); ?>" name = "duplicate_link_text" />
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field('save', 'xsspd_save_nonce'); ?>
		<input type="submit" name="save_settings" id="save_settings" class="button button-primary" value="Save Changes">
	</form>
</div>