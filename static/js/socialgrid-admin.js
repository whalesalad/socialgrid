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
        sg.add_screen = _$('</ul>').attr({
            'id': 'socialgrid-add-screen',
            'class': 'socialgrid-items'
        });
        
        // Hide the socialgrid items (the grid)
        sg.items.hide('slide', { direction: 'left' }, 500);
        
    },
    
    // Take a service and add it
    add_service: function(service) {
        
    }
}

_$(document).ready(function() {
    SocialGridAdmin.init();
})

