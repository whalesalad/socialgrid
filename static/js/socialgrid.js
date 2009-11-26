jQuery(function() {
    jQuery('ul#socialGrid li').each(function(index) {
        var button = jQuery(this);

        // Create a tooltip for each button
        var tooltipContent = new Array(
            '<span class="left"></span>',
            '<span class="center">'+jQuery(this).children('a').text()+'</span>',
            '<span class="right"></span>');

        var tooltip = jQuery('<div/>').attr('class', 'tooltip').html(tooltipContent.join('')).appendTo(jQuery(this));

        tooltip.css('left', -(tooltip.width()/2)+16).bind('click', function(event) {
            window.location = button.children('a').attr('href');
        });

        button.hover(function() {
            tooltip.siblings().stop();
            tooltip.fadeIn('fast');
        }, function() {
            tooltip.fadeOut('fast');
        });
    });
})
