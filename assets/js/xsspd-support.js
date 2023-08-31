jQuery(document).ready(function($) {
    "use strict";
    $('#xsspd_name , #xsspd_email , #xsspd_message').on('change',function(e){
        if(!$(this).val()){
            $(this).addClass("error");
        }else{
            $(this).removeClass("error");
        }
    });

    $('.xsspd_support_form').on('submit', function(e){
        e.preventDefault();
        $('.xs-send-email-notice').hide();
        $('.xsspd-mail-spinner').addClass('xsspd_is_active');
        $('#xsspd_name').removeClass("error");
        $('#xsspd_email').removeClass("error");
        $('#xsspd_message').removeClass("error"); 
        $.ajax({ 
            url:ajaxurl,
            type:'post',
            data:{'action':'xsspd_send_mail','data':$(this).serialize()},
            beforeSend: function(){
                if(!$('#xsspd_name').val()){
                    $('#xsspd_name').addClass("error");
                    $('.xs-send-email-notice').removeClass('notice-success');
                    $('.xs-send-email-notice').addClass('notice');
                    $('.xs-send-email-notice').addClass('error');
                    $('.xs-send-email-notice').addClass('is-dismissible');
                    $('.xs-send-email-notice p').html('Please fill all the fields');
                    $('.xs-send-email-notice').show();
                    window.scrollTo(0,0);
                    $('.xsspd-mail-spinner').removeClass('xsspd_is_active');
                    return false;
                }
                 if(!$('#xsspd_email').val()){
                    $('#xsspd_email').addClass("error");
                    $('.xs-send-email-notice').removeClass('notice-success');
                    $('.xs-send-email-notice').addClass('notice');
                    $('.xs-send-email-notice').addClass('error');
                    $('.xs-send-email-notice').addClass('is-dismissible');
                    $('.xs-send-email-notice p').html('Please fill all the fields');
                    $('.xs-send-email-notice').show();
                    window.scrollTo(0,0);
                    $('.xsspd-mail-spinner').removeClass('xsspd_is_active');
                    return false;
                }
                 if(!$('#xsspd_message').val()){
                    $('#xsspd_message').addClass("error");
                    $('.xs-send-email-notice').removeClass('notice-success');
                    $('.xs-send-email-notice').addClass('notice');
                    $('.xs-send-email-notice').addClass('error');
                    $('.xs-send-email-notice').addClass('is-dismissible');
                    $('.xs-send-email-notice p').html('Please fill all the fields');
                    $('.xs-send-email-notice').show();
                    window.scrollTo(0,0);
                    $('.xsspd-mail-spinner').removeClass('xsspd_is_active');
                    return false;
                }
                $(".xsspd_support_form :input").prop("disabled", true);
                $("#xsspd_message").prop("disabled", true);
                $('.xsspd-send-mail').prop('disabled',true);
            },
            success: function(res){
                $('.xs-send-email-notice').find('.xs-notice-dismiss').show();
                $('.xsspd-send-mail').prop('disabled',false);
                $(".xsspd_support_form :input").prop("disabled", false);
                $("#xsspd_message").prop("disabled", false);
                if(res.status == true){
                    $('.xs-send-email-notice').removeClass('error');
                    $('.xs-send-email-notice').addClass('notice');
                    $('.xs-send-email-notice').addClass('notice-success');
                    $('.xs-send-email-notice').addClass('is-dismissible');
                    $('.xs-send-email-notice p').html('Successfully sent');
                    $('.xs-send-email-notice').show();
                    $('.xsspd_support_form')[0].reset();
                }else{
                    $('.xs-send-email-notice').removeClass('notice-success');
                    $('.xs-send-email-notice').addClass('notice');
                    $('.xs-send-email-notice').addClass('error');
                    $('.xs-send-email-notice').addClass('is-dismissible');
                    $('.xs-send-email-notice p').html('Sent Failed');
                    $('.xs-send-email-notice').show();
                }
                $('.xsspd-mail-spinner').removeClass('xsspd_is_active');
            }

        });
    });
    $('.xs-notice-dismiss,.notice-dismiss').on('click',function(e){
        e.preventDefault();
        $(this).parent().hide();
        $(this).hide();
    });
});