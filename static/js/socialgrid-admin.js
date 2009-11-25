var _$ = jQuery;

SocialGridAdmin = {
    init: function() {
        var sg = this;
        
        this.items = _$('#socialgrid-home-screen');
        // sg.home_screen = this.items;
        
        // Make the items sortable
        _$('.socialgrid-items').sortable({
            containment: 'parent',
            items: 'li.socialgrid-item'
        });
        
        _$('li.socialgrid-item-add').click(function(event) {
            sg.select_service();
            return false;
        });
        
    },
    
    select_service: function() {
        var sg = this;
        
        // Construct the grid to choose new items
        sg.add_screen = _$('<div/>').addClass('socialgrid-pane').appendTo('#socialgrid-content');
        
        sg.add_screen.html('<h3>Choose a Service to Add</h3>');
        
        sg.add_grid = _$('<ul/>').attr({
            'id': 'socialgrid-add-screen',
            'class': 'socialgrid-items'
        }).appendTo(sg.add_screen);
        
        // For each of the disabled items, loop and create a new choosable item
        for (service in SG_SERVICES) {
            if (!SG_SERVICES[service]) {
                skeleton = SG_DEFAULTS[service];
                item = _$('<li/>')
                    .text(skeleton['name'])
                    .addClass(service)
                    .addClass('socialgrid-item')
                    .data('service', service);
                
                item.bind('click', function(event) {
                    sg.add_service(_$(this));
                    return false;
                });

                sg.add_grid.append(item);
            }
        }
        
        // Hide the socialgrid items (the grid)
        sg.items.hide('slide', { direction: 'left' }, 500);
        sg.add_screen.show('slide', { direction: 'right' }, 500);
        
    },
    
    // Take a service and add it
    add_service: function(s) {
        var sg = this,
            slug = s.data('service');
            service = SG_DEFAULTS[slug];
        
        sg.service_screen = _$('<div/>').addClass('socialgrid-pane').addClass(slug).appendTo('#socialgrid-content');
        
        sg.service_screen_html = new Array(
            '<div class="socialgrid-edit-header '+slug+'">',
                '<span class="socialgrid-service-name">'+service['name']+'</span>',
                '<span class="socialgrid-service-meta">'+service['url']+'</span>',
            '</div>',
            '<div class="socialgrid-edit-content">',
                '<p>Enter your '+service['name']+' username:</p>',
            '</div>'
            );
        
        sg.service_screen.html(sg.service_screen_html.join(''));
        
        sg.service_input = _$('<input/>').attr({
            'name': slug+'-input',
            'id': slug+'-input'
        }).appendTo('.socialgrid-edit-content');
        
        sg.service_footer = _$('<div/>').addClass('socialgrid-service-footer').hide();
        
        // Add a save button
        sg.service_footer.append(sg.create_edit_button('green', 'Save', function() {
            alert('save')
        }));
        
        // Add a cancel button
        sg.service_footer.append(sg.create_edit_button('red', 'Cancel', function() {
            sg.return_to_home();
        }));
        
        sg.add_screen.hide('slide', { direction: 'left' }, 500);
        sg.service_screen.show('slide', { direction: 'right' }, 500, function() {
            _$('#socialgrid-content').append(sg.service_footer);
            sg.service_footer.fadeIn();
        });
        
        
        
    },
    
    create_edit_button: function(type, text, callback) {
        // type can be red or green
        // callback is the function to exec when done
        button = _$('<a/>')
            .addClass('socialgrid-button')
            .addClass(type)
            .html('<span>'+text+'</span>')
            .bind('click', function() {
                callback();
            });
        
        return button;
    },
    
    return_to_home: function() {
        var sg = this;
        sg.items.siblings().remove();
        sg.items.fadeIn();
    }
}

_$(document).ready(function() {
    SocialGridAdmin.init();
})

