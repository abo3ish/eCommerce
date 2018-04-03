$(function() {
    'use strict';


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

// sign form toggle

    $(".sign-form span").click(function (){

        $(this).addClass("active").siblings().removeClass("active");
        if($(this).data('class') === 'signup'){
            $(this).css("color","#5cb85c");
        } else{
             $(this).siblings().css("color","#555");
        }
        $(".sign-form form").siblings("form").hide();
        $( "." + $(this).data('class') ).fadeIn(100);
    });

    $(".live").keyup( function (){
       $($(this).data("class")).text($(this).val());
    });

});