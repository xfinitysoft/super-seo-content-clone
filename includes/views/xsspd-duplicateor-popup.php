<?php $post = get_post(sanitize_text_field($_POST['post_id'])); ?>
<div id="" class="xsspd_modal" data-post-id="<?php echo esc_attr($_POST['post_id']); ?>">
	<div class="xsspd_modal-content">
		<div class="xsspd_modal_header">
			<h3> <?php echo esc_html__( 'Smart', 'super-seo-content-cloner').' '.esc_html(str_replace('_', ' ',ucfirst($post->post_type))).' '.esc_html__('Duplicator', 'super-seo-content-cloner'); ?></h3>
		</div>
		<div class="xsspd_modal-body">
			<div class="xsspd_accordin xsspd_dup_options_accordin">
				<div class="xsspd_accordin_header">
					<div class="xsspd_header_left">
						<h3><?php esc_html_e( 'Duplicate Options', 'super-seo-content-cloner' ); ?></h3>
					</div>
					<div class="xsspd_header_right">
						<a href="#" class="xsspd_more_options"><?php esc_html_e( 'Expand', 'super-seo-content-cloner' ); ?></a>
					</div>
					<div class="clear"></div>
				</div>
				<div class="xsspd_panel" style="display:none;">
					
					<?php do_action('xsspd_before_duplicate_options', $post); ?>
					
					<div class="xsspd_input_group xsspd_where_group">
						<div class="xsspd_input_group_label">
							<label><?php esc_html_e( 'Which content you want to duplicate', 'super-seo-content-cloner'); ?>.</label>
							<p class="xsspd_desc"><?php esc_html_e('Selected contents here will be duplicated.', 'super-seo-content-cloner'); ?></p>
						</div>
						<div class="xsspd_input_group_field">
							<label><?php esc_html_e( 'Featured Image', 'super-seo-content-cloner' ); ?>: <input type="checkbox" class="xsspd_duplicate_featured_img" checked> </label><span class="xsspd-sep">|</span>
							<label><?php esc_html_e( 'Taxonomies', 'super-seo-content-cloner' ); ?>: <input type="checkbox" class="xsspd_duplicate_taxonomies" checked> </label><span class="xsspd-sep">|</span>
							<label><?php esc_html_e( 'Meta Values', 'super-seo-content-cloner' ); ?>: <input type="checkbox" class="xsspd_duplicate_metas"></label>
						</div>
						<div class="clear"></div>
					</div>
					
					<?php do_action('xsspd_after_where_options', $post); ?>
					
					<div class="xsspd_input_group xsspd_taxonomies_group">
						<div class="xsspd_input_group_label">
							<label><?php esc_html_e( 'Select taxonomies which you want to duplicate', 'super-seo-content-cloner' ); ?>.</label>
							<p class="xsspd_desc"><?php esc_html_e('Select either you want to duplicate all taxonomies or specific taxonomies.', 'super-seo-content-cloner'); ?></p>
						</div>
						<div class="xsspd_input_group_field">
							<select class="xsspd_all_taxonomies">
								<option value="all_taxonomies"><?php esc_html_e( 'All Taxonomies', 'super-seo-content-cloner' ); ?></option>
								<option value="specific_taxonomies"><?php esc_html_e( 'Specific Taxonomies', 'super-seo-content-cloner' ); ?></option>
							</select>
							<?php $taxonomies = get_post_taxonomies( sanitize_text_field($_POST['post_id']) ); ?>
							<select class="xsspd_specific_taxonomies" multiple="true" style="display:none">
							<?php foreach($taxonomies as $taxonomy){ ?>
								<?php $taxonomy = get_taxonomy($taxonomy); ?>
								<option value="<?php echo esc_attr($taxonomy->name); ?>"><?php echo esc_html($taxonomy->labels->name); ?></option>
							<?php } ?>
							</select>
						</div>
						<div class="clear"></div>
					</div>
					
					<?php do_action('xsspd_after_taxonomies_options', $post); ?>
					
					<div class="xsspd_input_group xsspd_meta_group" style="display:none">
						<div class="xsspd_input_group_label">
							<label><?php esc_html_e( 'Select Meta Keys which you want to duplicate', 'super-seo-content-cloner' ); ?>.</label>
							<p class="xsspd_desc"><?php esc_html_e('Select either you want to duplicate all meta or specific meta.', 'super-seo-content-cloner'); ?></p>
						</div>
						<div class="xsspd_input_group_field">
							<select class="xsspd_all_meta_values">
								<option value="all_meta_values" ><?php esc_html_e( 'All Meta Keys', 'super-seo-content-cloner' ); ?></option>
								<option value="specific_meta_values"><?php esc_html_e( 'Specific Meta Keys', 'super-seo-content-cloner' ); ?></option>
							</select>
							<?php $meta = get_post_meta( sanitize_text_field($_POST['post_id']), '', true ); unset($meta['_thumbnail_id']); unset($meta['_wp_old_slug']); ?>
							<select class="xsspd_specific_meta_values" multiple="true" style="display:none">
							<?php foreach($meta as $key=>$value){ ?>
								<option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($key); ?></option>
							<?php } ?>
							</select>
						</div>
						<div class="clear"></div>
					</div>
					
					<?php do_action('xsspd_after_meta_options', $post); ?>
					
					<div class="xsspd_input_group xsspd_prefix_suffix_group">
						<div class="xsspd_left">
							<div class="xsspd_input_group_label">
								<label><?php esc_html_e( 'Title Prefix', 'super-seo-content-cloner' ); ?>.</label>
								<p class="xsspd_desc"><?php esc_html_e('This text will be added before the title of Duplicated copy.', 'super-seo-content-cloner'); ?></p>
							</div>
							<div class="xsspd_input_group_field">
								<input type="text" class="xsspd_title_prefix" />
							</div>
							<div class="clear"></div>
						</div>
						<div class="xsspd_right">
							<div class="xsspd_input_group_label">
								<label><?php esc_html_e( 'Title Suffix', 'super-seo-content-cloner' ); ?>.</label>
								<p class="xsspd_desc"><?php esc_html_e('This text will be added after the title of Duplicated copy.', 'super-seo-content-cloner'); ?></p>
							</div>
							<div class="xsspd_input_group_field">
								<input type="text" class="xsspd_title_suffix" value="Duplicate Copy" />
							</div>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</div>
					
					<?php do_action('xsspd_after_duplicate_options', $post); ?>
					
					<a href="#" class="xsspd_hide_options"><?php esc_html_e( 'Close', 'super-seo-content-cloner' ); ?></a>
					<div class="clear"></div>
				</div>
			</div>
			<div class="xsspd_popup_buttons">
				<span class="xsspd_success_message"></span>
				<span class="spinner xsspd_spinner"></span>
				<input type = "button" class="button button-primary button-large xsspd_close_popup" value = "<?php esc_attr_e( 'Cancel', 'super-seo-content-cloner' ); ?>" />
				<input type = "button" class="button button-primary button-large xsspd_add_new_replace_word" value = "<?php esc_attr_e( 'Add Replace Word', 'super-seo-content-cloner' ); ?>" />
				<input type = "button" class="button button-primary button-large xsspd_submit" value = "<?php esc_attr_e( 'Duplicate ', 'super-seo-content-cloner' ); ?>" />
			</div>
		</div>
		<div class="xsspd_modal_footer">
			<?php do_action('xsspd_extensions', $post); ?>
		</div>
	</div>
</div>