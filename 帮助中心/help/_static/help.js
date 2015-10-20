jQuery(document).ready(function(){
    $('.help_content .section img').each(function(){
        var img_src = $(this).attr('src');
        if(!img_src.indexOf('://')) {
            img_src = SITE_URL+img_src;
            $(this).attr('src',img_src);
        }
    });
});

