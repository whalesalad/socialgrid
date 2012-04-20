jQuery(function(_$) {
    _$('#socialGrid li').each(function(index) {
        var button = _$(this),
            FADE_SPEED = 150;

        // Create a tooltip for each button
        var tooltipContent = new Array(
            '<span class="left"></span>',
            '<span class="center">'+jQuery(this).children('a').text()+'</span>',
            '<span class="right"></span>');

        var tooltip = _$('<div/>').attr('class', 'tooltip').html(tooltipContent.join('')).appendTo(button),
            divisor = (_$('#socialGrid').hasClass('mini')) ? 8 : 16;

        tooltip.css('left', -(tooltip.width()/2)+divisor).bind('click', function(event) {
            window.location = button.children('a').attr('href');
        });

        button.hover(function() {
            tooltip.siblings().stop();
            tooltip.fadeIn(FADE_SPEED);
        }, function() {
            tooltip.fadeOut(FADE_SPEED);
        });
    });
});