$(document).ready(function(){
   

    $('.tabs-nav-main li').click(function(){
        $('.tabs-nav-main li').removeClass('active');
        $('.price-content').removeClass('active');
        var tid=$(this).attr('tab');
        $(this).addClass('active');
        $('#' +tid).addClass('active');
       });
});