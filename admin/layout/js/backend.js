$(function() {
    'use strict';

    $(".toggle").click( function (){
        $(this).toggleClass('selected').parent().next(".panel-body").slideToggle(250);

        if( $(this).hasClass('selected') ){

            $(this).html("<i class='fa fa-minus'></i>");
        } else{
            $(this).html("<i class='fa fa-plus'></i>");
        }
        
    });
     

    var nameerror = true,
        passerror = true;


    $('[placeholder]').focus(function() {
        $(this).attr("data-text", $(this).attr("placeholder"));
        $(this).attr("placeholder", "");
    });

    $('[placeholder]').blur(function() {
        $(this).attr("placeholder", $(this).attr("data-text"));
    });

    $('input').each(function() {
        if ($(this).attr('required') === 'required') {
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    /* show username error when BLUR */
    $('.username-alert').blur(function() {
        if ($(this).val().length < 2) {
            $(this).css('border', '1px solid #f00');
            $(this).parent().find('.alert').slideDown(300);
            nameerror = true;
        } else {
            $(this).css('border', '1px solid #080');
            $(this).parent().find('.alert').slideUp(300);

            nameerror = false;
        }
    });
    /* show password error when BLUR */
    $('.password').blur(function() {
        if ($(this).val().length < 2) {
            $(this).css('border', '1px solid #f00');
            $(this).parent().find('.alert').slideDown(300);
            passerror = true;
        } else {
            $(this).css('border', '1px solid #080');
            $(this).parent().find('.alert').slideUp(300);

            passerror = false;
        }
    });
    /* prevent the button to send when there are errors */

    /* show password when hover */

    $(".show-pass").on({
        mouseenter: function() { $(".password").attr("type", "text") },
        mouseleave: function() { $(".password").attr("type", "password") }

    });

    $('.confirm').click(function() {
        return confirm('Are u sure ?!');
    });

    /* toggle the options */

    $('.cats').click(function (){
        $(this ).children('.full-view').slideToggle(300);

    }); 

    $('.ordering span').click(function (){
        $(this).addClass('active').siblings('span').removeClass('active');
        if($(this).data('view') === 'full'){
            $('.full-view').slideDown(300);
        } else {
            $('.full-view').slideUp(300);   
        }
    });
    
});