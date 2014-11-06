// Utility
if ( typeof Object.create !== 'function' ) {
    Object.create = function( obj ) {
        function F(){};
        F.prototype = obj;
        return new F();
    }
}

(function($) {

    'use strict';

    var Modal = {

        init: function( options, elem ) {

            var self = this;

            self.elem = elem;
            self.$elem = $(elem);
            self.secondary = false;

            // Setup plugin options
            self.options = $.extend( {}, $.fn.ThemeBlvdModal.options, options );

            // Allow data overrides from HTML element
            for ( var index in self.options ) {
                if ( self.$elem.data(index) ) {
                    self.options[index] = self.$elem.data(index);
                }
            }

            // HTML element to show, or pull content from
            self.target = self.$elem.data('target');
            self.$target = $('#'+self.target);

            // Open modal
            self.$elem.off( 'click.themeblvd-modal' );
            self.$elem.on( 'click.themeblvd-modal', function(){

                // Setup popup
                self.popup = '';

                // Is another modal already open?
                if ( $('body').hasClass('themeblvd-modal-on') ) {
                    self.secondary = true;
                }

                // ID for modal
                self.id = self.target;

                // Build the popup if needed
                if ( self.options.build ) {
                    self.id += '_build';
                    self.build();
                }

                self.$modal_window =  $('#'+self.id);

                // Store target so it can be referenced outside of the object
                self.$modal_window.data('target', self.target);

                // Optional padding on content
                if ( self.options.padding ) {
                    self.$modal_window.find('.media-frame-content-inner').css('padding', '20px');
                }

                // on_load() callback
                self.options.on_load.call(self);

                // Bind close
                self.$modal_window.find('.media-modal-close').off( 'click.themeblvd-modal-close' );
                self.$modal_window.find('.media-modal-close').on( 'click.themeblvd-modal-close', function(){
                    self.options.on_cancel.call(self);
                    self.close();
                    return false;
                });

                // Save
                self.$modal_window.find('.media-button-insert').off( 'click.themeblvd-modal-insert' );
                self.$modal_window.find('.media-button-insert').on( 'click.themeblvd-modal-insert', function(){
                    self.options.on_save.call(self);
                    self.save();
                    self.close();
                    return false;
                });

                // Secondary button
                if ( self.options.button_secondary ) {
                    self.$modal_window.find('.media-button-secondary').off( 'click.themeblvd-modal-secondary' );
                    self.$modal_window.find('.media-button-secondary').on( 'click.themeblvd-modal-secondary', function(){
                        self.options.on_secondary.call(self);
                        self.close();
                        return false;
                    });
                }

                // Delete
                if ( self.options.button_delete ) {

                    var delete_msg = self.$elem.parent().is('.block-nav') ? themeblvd.delete_block : themeblvd.delete_item;

                    self.$modal_window.find('.media-button-delete').off( 'click.themeblvd-modal-delete' );
                    self.$modal_window.find('.media-button-delete').on( 'click.themeblvd-modal-delete', function(){
                        tbc_confirm( delete_msg, {'confirm': true}, function(r){
                            if(r) {
                                self.options.on_delete.call(self);
                                self.close();
                            }
                        });
                        return false;
                    });
                }

                // Display it
                self.display();

                // on_display() callback
                self.options.on_display.call(self);

                return false;
            });

        },

        build: function() {

            var self = this,
                content,
                markup = '<div id="%id%" class="themeblvd-modal-wrap build" style="display:none;"> \
                                <div class="themeblvd-modal %size%-modal %height%-height-modal media-modal wp-core-ui hide"> \
                                    <a class="media-modal-close" href="#" title="Close"> \
                                        <span class="media-modal-icon"></span> \
                                    </a> \
                                    <div class="media-modal-content"> \
                                        <div class="media-frame wp-core-ui hide-menu hide-router"> \
                                            <div class="media-frame-title"> \
                                                <h1>%title%</h1> \
                                            </div><!-- .media-frame-title (end) --> \
                                            <div class="media-frame-content"> \
                                                <div class="media-frame-content-inner"> \
                                                    <div class="content-mitt"> \
                                                        %content% \
                                                    </div> \
                                                </div><!-- .media-frame-content-inner (end) --> \
                                            </div><!-- .media-frame-content (end) --> \
                                            <div class="media-frame-toolbar"> \
                                                <div class="media-toolbar"> \
                                                    <div class="media-toolbar-primary"> \
                                                        <a href="#" class="button media-button button-primary button-large media-button-insert">%button_text%</a> \
                                                    </div> \
                                                </div><!-- .media-toolbar (end) --> \
                                            </div><!-- .media-frame-toolbar (end) --> \
                                        </div><!-- .media-frame (end) --> \
                                    </div><!-- .media-modal-content (end) --> \
                                 </div><!-- .themeblvd-modal (end) --> \
                             </div>';

            self.popup = markup;
            self.popup = self.popup.replace( '%id%', self.id);
            self.popup = self.popup.replace( '%size%', self.options.size);
            self.popup = self.popup.replace( '%height%', self.options.height);
            self.popup = self.popup.replace( '%title%', self.options.title );
            self.popup = self.popup.replace( '%button_text%', self.options.button );

            if ( self.options.code_editor ) {
                self.popup = self.popup.replace( '%content%', '<form><textarea id="'+self.id+'_editor" name="code"></textarea></form>' );
            }

            if ( self.options.form ) {
                self.popup = self.popup.replace( '%content%', '<div id="optionsframework"></div>' );
            }

            // Add to bottom of <body>. Will be removed
            // completely when modal is closed.
            $('body').append( self.popup );

            var $current = $('#'+self.id);

            // Duplicate Button?
            if ( self.options.button_secondary ) {
                $current.find('.media-toolbar').prepend('<div class="media-toolbar-secondary"><a href="#" class="button media-button button-secondary button-large media-button-secondary">'+self.options.button_secondary+'</a></div>');
            }

            // Delete button?
            if ( self.options.button_delete ) {
                $current.find('.media-toolbar').prepend('<div class="media-toolbar-secondary"><a href="#" class="button media-button button-secondary button-large media-button-delete">'+self.options.button_delete+'</a></div>');
            }

            if ( self.options.code_editor ) {

                if ( self.$elem.is('.tb-block-code-link') ) {
                    var field_name = self.$elem.closest('.block').data('field-name');
                    content = self.$elem.closest('.block').find('textarea[name="'+field_name+'[html]"]').val();
                } else {
                    content = self.$elem.closest('.textarea-wrap').find('textarea').val();
                }

                $('#'+self.id+'_editor').val(content);

            } else {

                // Set marker to send content back to when modal
                // is closed.
                self.$target.after('<div id="'+self.id+'_marker"></div>');

                // Apend user content
                if ( self.options.form ) {
                   $current.find('#optionsframework').append( self.$target );
                } else {
                   $current.find('.content-mitt').append( self.$target );
                }

            }

        },

        display: function() {

            var self = this,
                $body = $('body'),
                height;

            if ( self.secondary ) {

                // Another modal is already open, and this
                // one is appearing above it.
                $body.addClass('themeblvd-secondary-modal-on');
                self.$modal_window.find('.themeblvd-modal').removeClass('themeblvd-first-modal').addClass('themeblvd-second-modal');

                $body.find('.themeblvd-first-modal').prepend('<div class="inline-media-modal-backdrop"></div>');

            } else {

                $body.addClass('themeblvd-modal-on');

                // Disable scrolling on page behind modal
                $body.addClass('themeblvd-stop-scroll');

                // Add backdrop
                $body.append('<div id="themeblvd-modal-backdrop" class="media-modal-backdrop"></div>');

                // Add class to denote original base modal
                self.$modal_window.find('.themeblvd-modal').addClass('themeblvd-first-modal');

                // Close all open modals
                $('#themeblvd-modal-backdrop').off( 'click.themeblvd-modal-backdrop' );
                $('#themeblvd-modal-backdrop').on( 'click.themeblvd-modal-backdrop', function(){
                    self.options.on_cancel.call(self);
                    self.close_all();
                    return false;
                });
            }

            // Custom CSS class?
            if ( self.options.css_class ) {
                self.$modal_window.find('.themeblvd-modal').addClass( self.options.css_class );
            }

            // Show modal
            self.$modal_window.show();

            // Setup code editor with CodeMirror
            if ( self.options.code_editor && typeof CodeMirror !== 'undefined'  ) {

                var editor, mode;

                // Look for existing instance of this editor
                editor = $('#'+self.id+'_editor').data('CodeMirrorInstance');

                // Editor doesn't exist, so let's create one
                if ( ! editor ) {

                    if ( self.options.code_lang == 'css' || self.options.code_lang == 'javascript' ) {
                        mode = self.options.code_lang;
                    } else {
                        mode = {
                            name: "htmlmixed",
                            scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
                                           mode: null},
                                          {matches: /(text|application)\/(x-)?vb(a|script)/i,
                                           mode: "vbscript"}]
                        };
                    }

                    // Create editor instance
                    var editor = CodeMirror.fromTextArea(document.getElementById(self.id+'_editor'), {
                        mode: mode,
                        lineNumbers: true,
                        theme: 'themeblvd',
                        indentUnit: 4,
                        tabSize: 4,
                        indentWithTabs: true,
                        autofocus: true
                    });

                    // If editor was created successfully, store it for access later.
                    if ( editor ) {
                        $('#'+self.id+'_editor').data('CodeMirrorInstance', editor);
                    }
                }

            }

            // Adjust height if modal is not full size
            if ( self.options.height == 'auto' ) {

                var viewport_height = $(window).height();

                height = self.$modal_window.find('.media-frame-content-inner').outerHeight();
                height = 57 + height + 60; // 57 for title, 60 for footer

                if ( self.options.code_editor ) {
                    height += 2;
                } else {
                    height += 10;
                }

                self.$modal_window.find('.themeblvd-modal').css({
                    'max-height': height+'px',
                    'top': (viewport_height-height)/2+'px'
                });

                $(window).resize(function() {
                    self.$modal_window.find('.themeblvd-modal').css('top', ($(window).height()-height)/2+'px');
                });
            }

            // Make sure any scripts for options run
            if ( self.options.form ) {
                self.$modal_window.themeblvd('options', 'setup');
                self.$modal_window.themeblvd('options', 'bind');
                self.$modal_window.themeblvd('options', 'media-uploader');
                self.$modal_window.themeblvd('options', 'sortable');
            }

        },

        close: function() {

            var self = this,
                $body = $('body');

            // Put content from modal back to
            // original location.
            if ( self.options.build ) {
                $('#'+self.id+'_marker').after( self.$target ).remove();
            }

            // Unbind links within modal
            self.$modal_window.find('.media-button-secondary').off('click.themeblvd-modal-secondary');
            self.$modal_window.find('.media-modal-close').off('click.themeblvd-modal-close');
            self.$modal_window.find('.media-button-insert').off('click.themeblvd-modal-indert');
            self.$modal_window.find('.media-button-delete').off('click.themeblvd-modal-delete');

            // Hide or Remove modal
            if ( self.options.build ) {
                self.$modal_window.remove();
            } else {
                self.$modal_window.hide();
            }

            // Put everything outside of the modal back
            if ( self.secondary ) {

                // Closing a modal on top of another modal.
                // Remove body class and extra backdrop.
                $body.removeClass('themeblvd-secondary-modal-on');
                $body.find('.themeblvd-modal-wrap .inline-media-modal-backdrop').remove();


            } else {

                $body.removeClass('themeblvd-modal-on');

                // Allow page to be scrollable again
                $body.removeClass('themeblvd-stop-scroll');

                // Remove backdrop
                $('#themeblvd-modal-backdrop').remove();

            }
        },

        close_all: function() {
            $('.themeblvd-modal-wrap').each(function(){

                var $el = $(this), id, target;

                if ( $el.hasClass('build') ) {

                    id = $el.attr('id');
                    target = $el.data('target');

                    $('#'+id+'_marker').after( $('#'+target) ).remove();

                    $el.remove();

                } else {
                    $el.hide();
                }

                $('#themeblvd-modal-backdrop').remove();
                $('body').removeClass('themeblvd-modal-on themeblvd-secondary-modal-on themeblvd-stop-scroll');
            });
        },

        save: function() {

            var self = this;

            // If code editor, on save we can trasfer the code
            // from modal back to the textarea in the options.
            if ( self.options.code_editor ) {

                var editor = $('#'+self.id+'_editor').data('CodeMirrorInstance'),
                    content = editor.getValue(),
                    textarea;

                if ( self.$elem.is('.tb-block-code-link') ) {
                    var field_name = self.$elem.closest('.block').data('field-name');
                    textarea = self.$elem.closest('.block').find('textarea[name="'+field_name+'[html]"]');
                } else {
                    textarea = self.$elem.closest('.textarea-wrap').find('textarea');
                }

                textarea.val(content);

            }
        }
    };

    // Setup namespace
    $.fn.ThemeBlvdModal = function( options ) {
        return this.each(function(){
            var modal = Object.create( Modal );
            modal.init( options, this );
        });
    };

    $.fn.ThemeBlvdModal.options = {
        title: 'Setup',                 // Title of modal window
        button: 'Save',                 // Button text
        button_delete: '',              // Text for delete button in lower left - if blank, no button.
        button_secondary: '',           // Text for duplicate button in lower left - if blank, no button.
        size: 'large',                  // Width of modal - small, medium, large
        height: 'large',                // Height of modal - small, medium, large, or auto
        build: true,                    // Whether to build the HTML markup for the popup
        form: false,                    // Whether this modal is a set of options
        code_editor: false,             // Whether this modal is a code editor
        code_lang: 'html',              // If code editor, code language -- html, css, javascript
        padding: false,                 // Size of modal - small, medium, large
        send_back: null,                // An object of something you want send to info back to, which you can utilize from callbacks
        css_class: '',                  // Optional CSS class to add to modal
        on_load: function(modal){},     // Callback before modal window is displayed
        on_display: function(modal){},  // Callback just after modal has been displayed
        on_cancel: function(modal){},   // Callback when close button is clicked, before modal is closed
        on_save: function(modal){},     // Callback when save button is clicked, before modal is closed
        on_delete: function(modal){},   // Callback when delete button is clicked, before modal is closed
        on_secondary: function(modal){} // Callback when secondary button is clicked, before modal is closed
    };

})(jQuery);