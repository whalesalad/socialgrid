var _$ = jQuery;

SocialGridAdmin = {
    init: function() {
        var sg = this;
        
        this.items = _$('#socialgrid-home-screen');
        
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
        sg.add_screen = _$('<div/>').addClass('pane').appendTo('#socialgrid-content');
        
        sg.add_screen.html('<h3>Choose a Service</h3>');
        
        sg.add_grid = _$('<ul/>').attr({
            'id': 'socialgrid-add-screen',
            'class': 'socialgrid-items'
        }).appendTo(sg.add_screen);
        
        // For each of the disabled items, loop and create a new choosable item
        for (service in SG_SERVICES) {
            if (!SG_SERVICES[service]) {
                skeleton = SG_DEFAULTS[service];
                item = _$('<li/>').text(skeleton['name']).addClass(service).addClass('socialgrid-item');

                sg.add_grid.append(item);
            }
        }
        
        // Hide the socialgrid items (the grid)
        sg.items.hide('slide', { direction: 'left' }, 500);
        sg.add_screen.show('slide', { direction: 'right' }, 500);
        
    },
    
    // Take a service and add it
    add_service: function(service) {
        
    }
}

_$(document).ready(function() {
    SocialGridAdmin.init();
})

