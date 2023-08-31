jQuery(document).on('ready', function(){
	var $ = jQuery;
	$(document).on('click','.xsspd_duplicate',function(e){ 
		e.preventDefault();
		var spinner = $(this).parent().find('.xsspd_spinner').addClass('is-active').css('display','inline-block');
		$('body').css('pointer-events','none');
		$.ajax({
			url:xsspd_script_var.ajax_url,
			type:'post',
			data:{'action':'xsspd_load_duplicator_modal', 'post_id': $(this).attr('data-post-id')},
			success:function(res){
				$(res).insertAfter('#wpfooter');
				$('.xsspd_modal').find('.xsspd_specific_taxonomies').select2({
					placeholder: 'Select Taxonomies',
					theme: 'default xsspd_s2_specific_taxonomies',
					width:'60%'
				});
				$('.xsspd_modal').find('.xsspd_specific_meta_values').select2({
					placeholder: 'Select Meta Keys',
					theme: 'default xsspd_s2_specific_meta_values',
					width:'60%'
				});
			}
		}).always(function(jqXHR, textStatus, errorThrown){
			spinner.removeClass('is-active').css('display','none');
			$('body').css('pointer-events','auto');
			$('.xsspd_modal').show();
			if(textStatus !== 'success'){
				alert(errorThrown);
			}
		});
	}).on('change', '.xsspd_replace_in_metas,.xsspd_duplicate_metas', function(){
		if($(this).is(':checked')){
			$(this).closest('.xsspd_panel').find('.xsspd_meta_group').slideDown();
		}else{
			$(this).closest('.xsspd_panel').find('.xsspd_meta_group').slideUp();
		}
	}).on('change', '.xsspd_all_meta_values', function(){
		if($(this).val()=='specific_meta_values'){
			$(this).closest('.xsspd_panel').find('div.xsspd_s2_specific_meta_values').show();//find('.xsspd_meta_group span.select2-container').css('display','inline-block');
			$(this).closest('.xsspd_panel').find('span.xsspd_s2_specific_meta_values').css('display','inline-block');
		}else{
			$(this).closest('.xsspd_panel').find('.xsspd_s2_specific_meta_values').hide();//find('.xsspd_meta_group span.select2-container').hide();
		}
	}).on('change', '.xsspd_duplicate_taxonomies', function(){
		if($(this).is(':checked')){
			$(this).closest('.xsspd_panel').find('.xsspd_taxonomies_group').slideDown();
		}else{
			$(this).closest('.xsspd_panel').find('.xsspd_taxonomies_group').slideUp();
		}
	}).on('change', '.xsspd_all_taxonomies', function(){
		if($(this).val()=='all_taxonomies'){
			$(this).closest('.xsspd_panel').find('.xsspd_s2_specific_taxonomies').hide();//find('.xsspd_taxonomies_group span.select2-container').hide();
		}else{
			$(this).closest('.xsspd_panel').find('div.xsspd_s2_specific_taxonomies').show();//find('.xsspd_taxonomies_group span.select2-container').css('display','inline-block');
			$(this).closest('.xsspd_panel').find('span.xsspd_s2_specific_taxonomies').css('display','inline-block');
		}
	}).on('click', '.xsspd_more_options', function(e){
		e.preventDefault();
		$(this).closest('.xsspd_accordin').addClass('xsspd_open').find('.xsspd_panel').slideDown();
	}).on('click', '.xsspd_hide_options', function(e){
		e.preventDefault();
		$(this).closest('.xsspd_panel').slideUp().closest('xsspd_open').removeClass('xsspd_open');
	}).on('click', '.xsspd_remove', function(e){
		e.preventDefault();
		if(window.confirm('Are you sure you want to remove this word.?')){
			$(this).closest('.xsspd_accordin').remove();
		}
	}).on('click', '.xsspd_add_new_replace_word', function(e){
		e.preventDefault();
		$('.xsspd_popup_buttons .xsspd_spinner').addClass('is-active').css('display','inline-block');
		$('.xsspd_modal').css('pointer-events','none');
		$('.xsspd_success_message, .xsspd_error_message').css('display','none');
		$.ajax({
			type:'post',
			data:{'action': 'xsspd_load_new_accordin', 'post_id': $(this).closest('.xsspd_modal').attr('data-post-id')},
			url:xsspd_script_var.ajax_url,
			success:function(res){
				$(res).insertBefore('.xsspd_popup_buttons').slideDown().find('.xsspd_specific_meta_values').select2({
					placeholder: 'Select Meta Keys',
					containerCssClass:'xsspd_s2_specific_meta_values',
					width:'60%'
				});
			}
		}).always(function(jqXHR, textStatus, errorThrown){
			$('.xsspd_spinner').removeClass('is-active').css('display','none');
			$('.xsspd_modal').css('pointer-events','auto');
			if(textStatus !== 'success'){
				$('.xsspd_success_message, .xsspd_error_message').css('display','inline-block');
				$('.xsspd_success_message').text(errorThrown).removeClass('xsspd_success_message').addClass('xsspd_error_message');
			}
		});
	}).on('click','.xsspd_submit', function(e){
		e.preventDefault();
		$('.xsspd_success_message, .xsspd_error_message').css('display','none');
		var xsspd_dup_data = {};
		var find_replace_data = '';
		var duplication_options = {};
		duplication_options.contents = {};
		
		if($('.xsspd_accordin_replace_words_accordin').length > 0){
			find_replace_data = [];
		}
		
		// Set Actual Post ID which for Ajax request
		xsspd_dup_data.actual_post_id = $(this).closest('.xsspd_modal').attr('data-post-id');
		
		duplication_options.title_prefix = $('.xsspd_title_prefix').val();
		duplication_options.title_suffix = $('.xsspd_title_suffix').val();
		
		/*	*	*	*	*	*	*	*	*	*	*	*	*	*/
		/*	Settings content which have to be duplicated 	*/
		/*	*	*	*	*	*	*	*	*	*	*	*	*	*/
		
		// Set if Featured Image will duplicated or not
		if( $('.xsspd_dup_options_accordin .xsspd_duplicate_featured_img').is(':checked') ){
			duplication_options.contents.featured_image = 'yes';
		}else{
			duplication_options.contents.featured_image = 'no';
		}
		
		// Set if taxonomies will duplicated or not
		if( $('.xsspd_dup_options_accordin .xsspd_duplicate_taxonomies').is(':checked') ){
			duplication_options.contents.taxonomies = 'yes';
		}else{
			duplication_options.contents.taxonomies = 'no';
		}
		
		// Set if Meta will duplicated or not
		if( $('.xsspd_dup_options_accordin .xsspd_duplicate_metas').is(':checked') ){
			duplication_options.contents.meta = 'yes';
		}else{
			duplication_options.contents.meta = 'no';
		}
		
		// Set Which Taxonomies will duplicated or not
		if($('.xsspd_dup_options_accordin .xsspd_all_taxonomies').val()=='all_taxonomies'){
			duplication_options.taxonomies = 'all';
		}else{
			duplication_options.taxonomies = $('.xsspd_dup_options_accordin  .xsspd_specific_taxonomies').val();
		}
		
		// Set which Meta will duplicated or not
		if($('.xsspd_dup_options_accordin .xsspd_all_meta_values').val()=='all_meta_values'){
			duplication_options.meta = 'all';
		}else{
			duplication_options.meta = $('.xsspd_dup_options_accordin .xsspd_specific_meta_values').val();
		}
		
		/*	*	*	*	*	*	*	*	*	*	*	*	*/
		/* Setting data for Find and Replace process 	*/
		/*	*	*	*	*	*	*	*	*	*	*	*	*/
		
		// Loop to get all replace with words data 
		$('.xsspd_accordin_replace_words_accordin').each(function(){
			var replace_words_obj = {};
			
			// Set Replace Word
			replace_words_obj.replace = $(this).find('.replace_word').val();
			
			// Set Replace With Word
			replace_words_obj.replace_with = $(this).find('.replace_with_word').val();
			
			// Select if post title will be replaced or not
			if( $(this).find('.xsspd_replace_in_title').is(':checked') ){
				replace_words_obj.replace_title = 'yes';
			}else{
				replace_words_obj.replace_title = 'no';
			}
			
			// Select if post slug will be replaced or not
			if( $(this).find('.xsspd_replace_in_slug').is(':checked') ){
				replace_words_obj.replace_slug = 'yes';
			}else{
				replace_words_obj.replace_slug = 'no';
			}
			
			// Select if post content will be replaced or not
			if( $(this).find('.xsspd_replace_in_content').is(':checked') ){
				replace_words_obj.replace_content = 'yes';
			}else{
				replace_words_obj.replace_content = 'no';
			}
			
			// Select if post excerpt will be replaced or not
			if( $(this).find('.xsspd_replace_in_excerpt').is(':checked') ){
				replace_words_obj.replace_excerpt = 'yes';
			}else{
				replace_words_obj.replace_excerpt = 'no';
			}
			
			// Select if ALT tags in post content will be replaced or not
			if( $(this).find('.xsspd_replace_in_alt_tags').is(':checked') ){
				replace_words_obj.replace_img_alt_atts = 'yes';
			}else{
				replace_words_obj.replace_img_alt_atts = 'no';
			}
			
			/*if( $(this).find('.xsspd_replace_in_captions').is(':checked') ){
				replace_words_obj.replace_img_captions = 'yes';
			}else{
				replace_words_obj.replace_img_captions = 'no';
			}*/
			
			// Select if Meta Values will be replaced or not
			if( $(this).find('.xsspd_replace_in_metas').is(':checked') ){
				replace_words_obj.replace_meta = 'yes';
			}else{
				replace_words_obj.replace_meta = 'no';
			}
			
			// Select Which Meta Values will be replaced or not
			if($(this).find('.xsspd_all_meta_values').val()=='all_meta_values'){
				replace_words_obj.meta = 'all';
			}else{
				replace_words_obj.meta = $(this).find('.xsspd_specific_meta_values').val();
			}
			
			// Select case sestive or not
			if( $(this).find('.xsspd_case_senstive').is(':checked') ){
				replace_words_obj.case_senstive = 'yes';
			}else{
				replace_words_obj.case_senstive = 'no';
			}
			
			find_replace_data.push(replace_words_obj);
		});
		xsspd_dup_data.duplication_options = duplication_options;
		xsspd_dup_data.find_replace_data = find_replace_data;
		xsspd_process_data(xsspd_dup_data, function(){});
		/*
		
		
		ajax_duplicate_callback(xsspd_dup_data, $);*/
	}).on('click', '.xsspd_close_popup', function(e){
		e.preventDefault();
		if( window.confirm('Are you you want to cancel? \nAll the options you have set will be lost!') ){
			$('.xsspd_modal').remove();
			$('.xsspd_modal .xsspd_accordin_replace_words_accordin').remove();
			$('.xsspd_success_message').css('display','none')
		}
	})
	
	$(window).on('click', function(e){
		if($(e.target).hasClass('xsspd_modal') && window.confirm('Are you you want to cancel? \nAll the options you have set will be lost!')){
			$('.xsspd_modal').remove();
			$('.xsspd_modal .xsspd_accordin_replace_words_accordin').remove();
			$('.xsspd_success_message').css('display','none')
		}

	})
	
	var url = window.location.href;
    var q1 = 'xsspd_duplicate';
	var q2 = 'post';
	q1 = q1.replace(/[\[\]]/g, "\\$&");
	q2 = q2.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + q1 + "(=([^&#]*)|&|#|$)");
	results1 = regex.exec(url);
	regex = new RegExp("[?&]" + q2 + "(=([^&#]*)|&|#|$)");
    results2 = regex.exec(url);
	if(results1 && results2 && results1[2] && results2[2]){
		var post_id = decodeURIComponent( results2[2].replace(/\+/g, " ") );
		var xsspd_duplicate = decodeURIComponent( results1[2].replace(/\+/g, " ") );
		if(post && xsspd_duplicate == 'yes'){
			console.log(post);
			console.log(xsspd_duplicate);
			$('body').css('pointer-events','none');
			$('body').css('opacity', '0.3');
			$.ajax({
				url:xsspd_script_var.ajax_url,
				type:'post',
				data:{'action':'xsspd_load_duplicator_modal', 'post_id': post_id},
				success:function(res){
					$(res).insertAfter('#wpfooter');
					$('.xsspd_modal').find('.xsspd_specific_taxonomies').select2({
						placeholder: 'Select Taxonomies',
						containerCssClass : 'xsspd_s2_specific_taxonomies',
						width:'60%'
					});
					$('.xsspd_modal').find('.xsspd_specific_meta_values').select2({
						placeholder: 'Select Meta Keys',
						containerCssClass : 'xsspd_s2_specific_meta_values',
						width:'60%'
					});
				}
			}).always(function(jqXHR, textStatus, errorThrown){
				$('body').css('pointer-events','auto');
				$('body').css('opacity', '1');
				$('.xsspd_modal').show();
				if(textStatus !== 'success'){
					alert(errorThrown);
				}
			});
		}
	}
function xsspd_process_data(data){
	var xsspd_extensions = [],
	$ = jQuery;
	if( $('.xsspd_extensions').length ){
		$('.xsspd_extensions').each(function(){
			xsspd_extensions.push($(this).val());
		});
	}
	$(xsspd_extensions).each(function(i, v){
		callback = v+'_process_data';
		if(typeof(window[callback]) == 'function'){
			data = window[callback](data);
		}
		//console.log(window);
	});
	if(data){
		ajax_duplicate_callback(data);
	}
	//console.log(data);
}
function ajax_duplicate_callback(data){	
$('.xsspd_popup_buttons  .xsspd_spinner').addClass('is-active').css('display','inline-block');
$('body').css('pointer-events','none');
$.ajax({
	url : xsspd_script_var.ajax_url,
	data : {'action' : 'xsspd_process_duplication', 'duplication_data' : data},
	content : 'json',
	'type' : 'post',
	success : function(res){
		$('.xsspd_success_message, .xsspd_error_message').css('display','inline-block');
		if(res.status){
			$('.xsspd_success_message').text(res.message);
			if(res.redirect_to) window.location.href = res.redirect_to;
		}else{
			$('.xsspd_success_message').text(res.message).removeClass('xsspd_success_message').addClass('xsspd_error_message');
		}
	}
}).always(function(jqXHR, textStatus, errorThrown){
	$('.xsspd_spinner').removeClass('is-active').css('display','none');
	$('body').css('pointer-events','auto');
	console.log(textStatus);
	if(textStatus !== 'success'){
		$('.xsspd_success_message, .xsspd_error_message').css('display','inline-block');
		$('.xsspd_success_message').text(errorThrown).removeClass('xsspd_success_message').addClass('xsspd_error_message');
	}
});
}
});

