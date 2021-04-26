$(document).ready(function() {
    $('.toggler').click(function(){
        $(this).toggleClass('toggled');
        $('.menu_wrapper').toggleClass('shown');
        $('.menu_focus').toggleClass('shown');
    });
});