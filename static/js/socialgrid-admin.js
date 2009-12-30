var _$ = jQuery;

SocialGridAdmin = {
    init: function() {
        var sg = this;
        
        this.home_screen = _$('#socialgrid-home-screen');
        this.dropzone = _$('#socialgrid-drop-delete');
        this.settings_button = _$('#socialgrid-settings-button');

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
            },
            update: function(event, ui) {
                var items = sg.items.children('.socialgrid-item');
                var post_array = new Array();
                for (var i=0; i < items.length; i++) {
                    var item = _$(items[i]);
                    var klass = item.attr('class').split(' ')[1];
                    post_array.push(klass);
                };
                sg.rearrange_services(post_array);
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
        
        sg.settings_button.bind('click', function(event) {
            sg.open_settings();
            return false;
        });
    },
    
    open_settings: function() {
        var sg = this;
        
        sg.settings_screen = _$('<div/>')
            .addClass('socialgrid-pane')
            .addClass('socialgrid-settings-pane')
            .appendTo('#socialgrid-content');
        
        settings_html = [
            '<h3>SocialGrid Settings</h3>',
            '<div class="field">',
                '<p>You can use 16px \'mini\' icons instead of the large 32px icons. This setting is reflected on your website, not in the SocialGrid admin.</p>',
                '<input type="checkbox" name="sg-mini-icons" id="sg-mini-icons" />',
                '<label for="sg-mini-icons">Enable Mini Icons</label>',
            '</div>',
            '<div class="field">',
                '<p>Some like to let their icons to do all the talking, and don\'t need no stinkin\' tooltips.</p>',
                '<input type="checkbox" name="sg-disable-tooltips" id="sg-disable-tooltips" />',
                '<label for="sg-disable-tooltips">Disable Tooltips Completely</label>',
            '</div>'
        ];
        
        sg.settings_screen.html(settings_html.join(""));
        
        if (SG_MINI_ICONS)
            _$('#sg-mini-icons').attr('checked', 'checked');
        
        if (SG_DISABLE_TOOLTIPS)
            _$('#sg-disable-tooltips').attr('checked', 'checked');
        
        sg.reset_button = sg.create_button('Reset', function() {
            // will reset all of SG
            sg.settings_confirm_reset();
        }).appendTo('#socialgrid-header');
        
        // SERVICE FOOTER
        sg.settings_footer_container = _$('<div/>').addClass('socialgrid-footer-container').appendTo(sg.settings_screen);
        sg.settings_footer = _$('<ul/>').addClass('socialgrid-footer').appendTo(sg.settings_footer_container);
        
        // Add a save button
        sg.settings_footer.append(sg.create_edit_button('green', 'Save', function() {
            sg.settings_screen.trigger('save_settings');
        }));
        
        // Add a cancel button
        sg.settings_footer.append(sg.create_edit_button('red', 'Cancel', function() {
            sg.reset_button.fadeOut('fast', function(){
                sg.reset_button.remove();
            });
            sg.return_to_home(300);
        }));
        
        // Hide the socialgrid items (the grid)
        // sg.home_screen.hide('slide', { direction: 'left' }, 500);
        sg.hide_homescreen();
        sg.settings_screen.show('slide', { direction: 'right' }, 500);
        
        sg.settings_screen.bind('save_settings', function(event) {
            var icon_checkbox = _$('#sg-mini-icons'),
                tooltip_checkbox = _$('#sg-disable-tooltips');

            sg.settings = {
                icon_size: (icon_checkbox.attr('checked')) ? 'mini' : 'standard',
                tooltips: (tooltip_checkbox.attr('checked')) ? 'disabled' : 'enabled'
            }
            
            sg.save_settings(sg.settings);
            
            sg.reset_button.fadeOut('fast', function(){
                sg.reset_button.remove();
            });
            sg.return_to_home();
        });
        
    },
    
    settings_confirm_reset: function() {
        // confirm the reset of all settings
        var sg = this;
        
        sg.reset_button.fadeOut();
        
        sg.reset_screen = _$('<div/>')
            .addClass('socialgrid-pane')
            .addClass('socialgrid-settings-pane')
            .appendTo('#socialgrid-content');
        
        reset_html = [
            '<h3>Reset SocialGrid</h3>',
            '<p>If you are having problems with SG or would simply like to start fresh, you can reset your settings.</p>',
            '<p>Keep in mind, resetting SocialGrid will remove all of your icons from your website and give you a fresh clean slate to work with.</p>',
        ];
        
        sg.reset_screen.html(reset_html.join(""));
        
        // RESET FOOTER
        sg.reset_footer_container = _$('<div/>').addClass('socialgrid-footer-container').appendTo(sg.reset_screen);
        sg.reset_footer = _$('<ul/>').addClass('socialgrid-footer').appendTo(sg.reset_footer_container);
        
        // Add a save button
        sg.reset_footer.append(sg.create_edit_button('green', 'Yes, Reset', function() {
            // RESET LOGIC
            sg.settings_do_reset();
        }));
        
        // Add a cancel button
        sg.reset_footer.append(sg.create_edit_button('red', 'Cancel', function() {
            sg.reset_screen.hide('slide', { direction: 'right' }, 500);
            sg.settings_screen.show('slide', { direction: 'left' }, 500);
            sg.reset_button.fadeIn();
        }));
        
        sg.switch_screen(sg.reset_screen, sg.settings_screen);
        
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
                var skeleton = SG_DEFAULTS[service];
                var item = _$('<li/>')
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
        // sg.home_screen.hide('slide', { direction: 'left' }, 500);
        sg.hide_homescreen();
        sg.add_screen.show('slide', { direction: 'right' }, 500);
        
    },
    
    /*
        ADD SERVICE
    */
    add_service: function(s) {
        var sg = this,
            slug = s.data('service');
            service = SG_DEFAULTS[slug];
        
        sg.service_screen = _$('<div/>').addClass('socialgrid-pane').addClass(slug).appendTo('#socialgrid-content');
        
        if (slug == 'rss') {
            sg.service_screen_html = new Array(
                '<div class="socialgrid-edit-header '+slug+'">',
                    '<span class="socialgrid-service-name">'+service['name']+'</span>',
                    '<span class="socialgrid-service-meta">Your WordPress RSS Feed</span>',
                '</div>',
                '<div class="socialgrid-edit-content">',
                    '<p><strong>This SocialGrid button will link to the RSS feed that WordPress automatically creates for you.</strong></p>',
                    '<p>If you would like to customize your RSS feed (ex, Feedburner), there are plugins available to do that.</p>',
                '</div>');
            
            sg.service_screen.html(sg.service_screen_html.join(''));
        
        } else if (slug == 'technorati') {
            sg.service_screen_html = new Array(
                '<div class="socialgrid-edit-header '+slug+'">',
                    '<span class="socialgrid-service-name">'+service['name']+'</span>',
                    '<span class="socialgrid-service-meta">Your Technorati Profile</span>',
                '</div>',
                '<div class="socialgrid-edit-content">',
                    '<p><strong>This SocialGrid button will link to the Technorati profile for your blog.</strong></p>',
                    '<p>You don\'t need to enter a username, as the profile link is based on your blog\'s URL, which is filled in automatically.</p>',
                '</div>');
            
            sg.service_screen.html(sg.service_screen_html.join(''));
        } else {
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
            
            //
            // SERVICE INPUT
            //
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
                if (event.keyCode == 13) sg.service_screen.trigger('add_service');
            });
        }
        
        //
        // SERVICE FOOTER
        //
        sg.service_footer_container = _$('<div/>').addClass('socialgrid-footer-container').appendTo(sg.service_screen);
        sg.service_footer = _$('<ul/>').addClass('socialgrid-footer').appendTo(sg.service_footer_container);
        
        // Add a save button
        sg.service_footer.append(sg.create_edit_button('green', 'Add', function() {
            sg.service_screen.trigger('add_service');
        }));
        
        // Add a cancel button
        sg.service_footer.append(sg.create_edit_button('red', 'Cancel', function() {
            sg.return_to_home(300);
        }));
        
        sg.add_screen.hide('slide', { direction: 'left' }, 500);
        sg.service_screen.show('slide', { direction: 'right' }, 500, function() {
            if (slug != 'rss') return;
            if (slug != 'technorati') return;
            sg.service_input.focus();
        });
        
        // Create the binding for submission
        sg.service_screen.bind('add_service', function(event) {
            if (slug == 'rss') {
                username = 'rss';
            } else if (slug == 'technorati') {
                username = 'technorati';
            } else {
                username = sg.service_input.val();
            }
            
            if (!username) {
                alert('Please enter a valid username!');
            } else {
                sg.remote_edit(slug, username);
            }
        });
    },
    
    /*
        EDIT SERVICE
    */
    edit_service: function(slug) {
        var sg = this,
            username = SG_SERVICES[slug],
            service = SG_DEFAULTS[slug];
        
        sg.service_screen = _$('<div/>').addClass('socialgrid-pane').addClass(slug).appendTo('#socialgrid-content');
        
        if (slug == 'rss') {
            sg.service_screen_html = new Array(
                '<div class="socialgrid-edit-header '+slug+'">',
                    '<span class="socialgrid-service-name">'+service['name']+'</span>',
                    '<span class="socialgrid-service-meta">Your WordPress RSS Feed</span>',
                '</div>',
                '<div class="socialgrid-edit-content">',
                    '<p><strong>This SocialGrid button will link to the RSS feed that WordPress automatically creates for you.</strong></p>',
                    '<p>If you would like to customize your RSS feed (ex, Feedburner), there are plugins available to do that.</p>',
                '</div>');
            
            sg.service_screen.html(sg.service_screen_html.join(''));
            
            // Create the footer for the buttons
            sg.service_footer_container = _$('<div/>').addClass('socialgrid-footer-container').appendTo(sg.service_screen);
            sg.service_footer = _$('<ul/>').addClass('socialgrid-footer').appendTo(sg.service_footer_container);
            
            // Add a cancel button
            sg.service_footer.append(sg.create_edit_button('red', 'Back', function() {
                sg.return_to_home();
            }));
        
        } else if (slug == 'technorati') {
            
            sg.service_screen_html = new Array(
                '<div class="socialgrid-edit-header '+slug+'">',
                    '<span class="socialgrid-service-name">'+service['name']+'</span>',
                    '<span class="socialgrid-service-meta">Your Technorati Profile</span>',
                '</div>',
                '<div class="socialgrid-edit-content">',
                    '<p><strong>This SocialGrid button will link to the Technorati profile for your blog.</strong></p>',
                    '<p>You don\'t need to enter a username, as the profile link is based on your blog\'s URL, which is filled in automatically.</p>',
                '</div>');
            
            sg.service_screen.html(sg.service_screen_html.join(''));
            
            // Create the footer for the buttons
            sg.service_footer_container = _$('<div/>').addClass('socialgrid-footer-container').appendTo(sg.service_screen);
            sg.service_footer = _$('<ul/>').addClass('socialgrid-footer').appendTo(sg.service_footer_container);
            
            // Add a cancel button
            sg.service_footer.append(sg.create_edit_button('red', 'Back', function() {
                sg.return_to_home();
            }));
            
        } else {
            sg.service_screen_html = new Array(
                '<div class="socialgrid-edit-header '+slug+'">',
                    '<span class="socialgrid-service-name">'+service['name']+'</span>',
                    '<span class="socialgrid-service-meta">'+sg.parse_url(service['url'], username)+'</span>',
                '</div>',
                '<div class="socialgrid-edit-content">',
                    '<p>Enter your '+service['name']+' username:</p>',
                '</div>');
                
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
                    sg.service_screen.trigger('save_service');
            });

            // Create the footer for the buttons
            sg.service_footer_container = _$('<div/>').addClass('socialgrid-footer-container').appendTo(sg.service_screen);
            sg.service_footer = _$('<ul/>').addClass('socialgrid-footer').appendTo(sg.service_footer_container);
            

            // Add a save button
            sg.service_footer.append(sg.create_edit_button('green', 'Save', function() {
                sg.service_screen.trigger('save_service');
            }));

            // Add a cancel button
            sg.service_footer.append(sg.create_edit_button('red', 'Cancel', function() {
                sg.return_to_home();
            }));
        }
        
        // sg.home_screen.hide('slide', { direction: 'left' }, 500);
        sg.hide_homescreen();
        sg.service_screen.show('slide', { direction: 'right' }, 500, function() {
            if (slug != 'rss') return;
            if (slug != 'technorati') return;
            sg.service_input.focus();
        });
        
        // Create the binding for submission
        sg.service_screen.bind('save_service', function(event) {
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
            _$.ajax({
                url: window.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'update_socialgrid_service',
                    'service': service,
                    'username': username
                },

                success: function() {
                    SG_SERVICES[service] = username;
                    
                    sg.return_to_home();
                },

                error: function() {
                    alert('error');
                }
            });
        } else {
            // CREATE
            if (service == 'rss') username == 'rss';
            _$.ajax({
                url: window.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'add_socialgrid_service',
                    'service': service,
                    'username': username
                },

                success: function() {
                    SG_SERVICES[service] = username;
                    
                    item = _$('<li/>')
                        .text(SG_DEFAULTS[service]['name'])
                        .addClass('socialgrid-item')
                        .addClass(service)
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
    
    rearrange_services: function(services) {
        _$.ajax({
            url: window.ajaxurl,
            type: 'POST',
            data: {
                'action': 'rearrange_socialgrid_services',
                'services': services.join('|')
            },

            success: function(response) {
                console.log(response);
            },

            error: function() {
                alert('error');
            }
        });
    },
    
    // Remove a serivce
    remove_service: function(service) {
        var service_name = service.attr('class').split(' ')[1];

        // Hide the service
        service.hide();
        
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
    
    // Save Icon Size
    save_settings: function() {
        var sg = this;
        
        _$.ajax({
            url: window.ajaxurl,
            type: 'POST',
            data: {
                'action': 'save_socialgrid_settings',
                'icon_size': sg.settings.icon_size,
                'tooltips': sg.settings.tooltips
            },
            
            success: function(response) {
                window.SG_MINI_ICONS = (sg.settings.icon_size == 'mini') ? true : false;
                window.SG_DISABLE_TOOLTIPS = (sg.settings.tooltips == 'disabled') ? true : false;
            },
            
            error: function() {
                alert('error');
            }
        });
    },
    
    settings_do_reset: function () {
        var sg = this;
        
        _$.ajax({
            url: window.ajaxurl,
            type: 'POST',
            data: {
                'action': 'master_socialgrid_reset'
            },
            
            success: function() {
                window.location = window.location+'&reloaded=true';
            },
            
            error: function() {
                alert('error');
            }
        });
        
    },
    
    //////////
    
    parse_url: function(url, username) {
        var replacement = /\%s/;
        username = '<span class="socialgrid-username-replace">'+username+'</span>';
        return url.replace(replacement, username);
    },
    
    create_edit_button: function(type, text, callback) {
        // type can be red or green
        // callback is the function to exec when done
        button = _$('<li/>').append(_$('<a/>')
            .attr('href', '#')
            .addClass('socialgrid-button')
            .addClass(type)
            .html('<span>'+text+'</span>')
            .bind('click', function() {
                callback();
                return false;
            }));
        
        return button;
    },
    
    create_button: function(text, callback) {
        button = _$('<a/>')
            .attr('href', '#')
            .addClass('socialgrid-button')
            .html('<span>'+text+'</span>')
            .bind('click', function() {
                if (callback) { callback(); }
                return false;
            });
        
        return button;
    },
    
    hide_homescreen: function() {
        var sg = this;
        sg.settings_button.fadeOut('fast');
        sg.home_screen.hide('slide', { direction: 'left' }, 500);
    },
    
    return_to_home: function(speed) {
        var sg = this;
        
        var s = 700;
        
        sg.home_screen.siblings(':visible').hide('slide', { direction: 'right' }, s, function() {
            _$(this).remove();
        });
        sg.home_screen.show('slide', { direction: 'left' }, s);
        
        sg.settings_button.fadeIn('fast');
    },
    
    switch_screen: function(screen_in, screen_out) {
        screen_out.hide('slide', { direction: 'left' }, 500);
        screen_in.show('slide', { direction: 'right' }, 500);
    }
};