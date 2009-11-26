var _$ = jQuery;

SocialGridAdmin = {
    init: function() {
        var sg = this;
        
        this.home_screen = _$('#socialgrid-home-screen');
        this.dropzone = _$('#socialgrid-drop-delete');
        
        this.items = _$('.socialgrid-items');
        
        sg.items.sortable({
            opacity: .5,
            containment: sg.home_screen,
            items: '.socialgrid-item',
            start: function() {
                sg.dropzone.fadeIn();
            },
            stop: function() {
                sg.dropzone.fadeOut();
            }
        });

        sg.dropzone.droppable({
            accept: '.socialgrid-item',
            hoverClass: 'acceptable',
            drop: function(event, ui) {
                var service_item = ui.draggable;
                sg.remove_service(service_item);
            }
        });
        
        _$('li.socialgrid-item-add').click(function(event) {
            sg.select_service();
            return false;
        });
        
        _$(sg.items).bind('dblclick', function(event) {
            if (_$(event.target).is('.socialgrid-item')) {
                var target = _$(event.target);
                // We shall edit this shenanigans :D
                var service_name = target.attr('class').split(' ')[1];
                sg.edit_service(service_name);
            };
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
        sg.home_screen.hide('slide', { direction: 'left' }, 500);
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
                '<span class="socialgrid-service-meta">'+sg.parse_url(service['url'], 'username')+'</span>',
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
        
        sg.service_input.bind('keyup', function(event) {
            var value = (_$(this).val()) ? _$(this).val() : 'username';
            
            _$('span.socialgrid-service-meta').html(sg.parse_url(service['url'], value));
            
        });

        // Bind to the enter key to trigger the add_service
        sg.service_input.bind('keypress', function(event) {
            if (event.keyCode == 13)
                sg.service_screen.trigger('add_service');
        });
        
        
        sg.service_footer = _$('<div/>').addClass('socialgrid-service-footer').hide();
        
        // Add a save button
        sg.service_footer.append(sg.create_edit_button('green', 'Save', function() {
            sg.service_screen.trigger('add_service');
        }));
        
        // Add a cancel button
        sg.service_footer.append(sg.create_edit_button('red', 'Cancel', function() {
            sg.return_to_home(300);
        }));
        
        sg.add_screen.hide('slide', { direction: 'left' }, 500);
        sg.service_screen.show('slide', { direction: 'right' }, 500, function() {
            sg.service_screen.append(sg.service_footer);
            sg.service_input.focus();
            sg.service_footer.fadeIn();
        });
        
        // Create the binding for submission
        sg.service_screen.bind('add_service', function(event) {
            username = sg.service_input.val();
            if (!username) {
                alert('Please enter a valid username!');
            } else {
                sg.remote_edit(slug, username);
            }
            
        });
    },
    
    edit_service: function(slug) {
        var sg = this,
            username = SG_SERVICES[slug],
            service = SG_DEFAULTS[slug];
        
        sg.service_screen = _$('<div/>').addClass('socialgrid-pane').addClass(slug).appendTo('#socialgrid-content');
        
        sg.service_screen_html = new Array(
            '<div class="socialgrid-edit-header '+slug+'">',
                '<span class="socialgrid-service-name">'+service['name']+'</span>',
                '<span class="socialgrid-service-meta">'+sg.parse_url(service['url'], username)+'</span>',
            '</div>',
            '<div class="socialgrid-edit-content">',
                '<p>Enter your '+service['name']+' username:</p>',
            '</div>'
            );
        
        sg.service_screen.html(sg.service_screen_html.join(''));
        
        sg.service_input = _$('<input/>').attr({
            'name': slug+'-input',
            'id': slug+'-input'
        }).val(username).appendTo('.socialgrid-edit-content');
        
        sg.service_input.bind('keyup', function(event) {
            var value = (_$(this).val()) ? _$(this).val() : 'username';
            
            _$('span.socialgrid-service-meta').html(sg.parse_url(service['url'], value));
            
        });

        // Bind to the enter key to trigger the add_service
        sg.service_input.bind('keypress', function(event) {
            if (event.keyCode == 13)
                sg.service_screen.trigger('add_service');
        });
        
        sg.service_footer = _$('<div/>').addClass('socialgrid-service-footer').hide();
        
        // Add a save button
        sg.service_footer.append(sg.create_edit_button('green', 'Save', function() {
            sg.service_screen.trigger('add_service');
        }));
        
        // Add a cancel button
        sg.service_footer.append(sg.create_edit_button('red', 'Cancel', function() {
            sg.return_to_home();
        }));
        
        sg.home_screen.hide('slide', { direction: 'left' }, 500);
        sg.service_screen.show('slide', { direction: 'right' }, 500, function() {
            sg.service_screen.append(sg.service_footer);
            sg.service_input.focus();
            sg.service_footer.fadeIn();
        });
        
        // Create the binding for submission
        sg.service_screen.bind('add_service', function(event) {
            username = sg.service_input.val();
            if (!username) {
                alert('Please enter a valid username!');
            } else {
                sg.remote_edit(slug, username);
            }
            
        });
    },
    
    // RPC to save a new or edit a current service
    remote_edit: function(service, username) {
        var sg = this;

        if (SG_SERVICES[service]) {
            // EDIT
        } else {
            // CREATE
            _$.ajax({
                url: window.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'add_socialgrid_service',
                    'service': service,
                    'username': username
                },

                success: function() {
                    SG_SERVICES[service] = true;
                    
                    item = _$('<li/>')
                        .text(SG_DEFAULTS[service]['name'])
                        .addClass(service)
                        .addClass('socialgrid-item')
                        .data('service', service);
                    
                    _$('.socialgrid-items').children('li.socialgrid-item-add').before(item);
                    
                    sg.return_to_home();

                },

                error: function() {
                    alert('error');
                }
            });

        };
    },
    
    remote_rearrange: function() {
        
    },
    
    // Remove a serivce
    remove_service: function(service) {
        var service_name = service.attr('class').split(' ')[1];
        // console.log(service_name);
        _$.ajax({
            url: window.ajaxurl,
            type: 'POST',
            data: {
                'action': 'remove_socialgrid_service',
                'service': service_name,
            },

            success: function() {
                SG_SERVICES[service_name] = false;
                service.remove();
            },

            error: function() {
                alert('error');
            }
        });
    },
    
    parse_url: function(url, username) {
        var replacement = /\%s/;
        username = '<span class="socialgrid-username-replace">'+username+'</span>';
        return url.replace(replacement, username);
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
    
    return_to_home: function(speed) {
        var sg = this;
        
        var s = 700;
        
        sg.home_screen.siblings(':visible').hide('slide', { direction: 'right' }, s, function() {
            _$(this).remove();
        });
        sg.home_screen.show('slide', { direction: 'left' }, s);
    }
};