define([
    'jquery'
], function($) {
    $('.social-login-container .block-content ul li a').click(function(e) {
        var provider = $(this).attr('class');
        var url = '/elquchiri_redirect/social/login/provider/' + provider;

        window.open(url,"","menubar=no, status=no, scrollbars=no, menubar=no, width=500,height=500");
        e.preventDefault();
    });
});