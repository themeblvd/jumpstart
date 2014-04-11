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

            // Delete button
            if ( self.$elem.parent().is('.content-block-nav') ) {
                self.options.button_delete = themeblvd.delete_text;
            }

            // Open modal
            self.$elem.on( 'click', function(){

                // Setup popup
                self.popup = '';

                // ID for modal
                self.id = self.target;

                // Build the popup if needed
                if ( self.options.build ) {
                    self.id += '_build';
                    self.build();
                }

                self.$modal_window =  $('#'+self.id);

                // Optional padding on content
                if ( self.options.padding ) {
                    self.$modal_window.find('.media-frame-content-inner').css('padding', '20px');
                }

                // Setup() callback
                self.options.on_load.call(self);

                // Bind close
                self.$modal_window.find('.media-modal-close, .media-modal-backdrop').on( 'click', function(){
                    self.options.on_cancel.call(self);
                    self.close();
                    return false;
                });

                // Save
                self.$modal_window.find('.media-button-insert').on( 'click', function(){
                    self.options.on_save.call(self);
                    self.save();
                    self.close();
                    return false;
                });

                // Delete
                if ( self.options.button_delete ) {

                    var delete_msg = self.$elem.parent().is('.content-block-nav') ? themeblvd.delete_block : themeblvd.delete_item;

                    self.$modal_window.find('.media-button-delete').on( 'click', function(){
                        tbc_confirm( delete_msg, {'confirm': true}, function(r){
                            if(r) {
                                self.options.on_delete.call(self);
                                if ( self.$elem.parent().is('.content-block-nav') ) {
                                    self.delete_block();
                                }
                                self.close();
                            }
                        });
                        return false;
                    });
                }

                // Display it
                self.display();

                return false;
            });

        },

        build: function() {

            var self = this,
                content,
                markup = '<div id="%id%" class="themeblvd-modal-wrap" style="display:none;"> \
                                <div class="themeblvd-modal %size%-modal media-modal wp-core-ui hide"> \
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
                                 <div class="media-modal-backdrop"></div> \
                             </div>';

            self.popup = markup;
            self.popup = self.popup.replace( '%id%', self.id);
            self.popup = self.popup.replace( '%size%', self.options.size);
            self.popup = self.popup.replace( '%title%', self.options.title );
            self.popup = self.popup.replace( '%button_text%', self.options.button );

            if ( self.options.code_editor ) {
                self.popup = self.popup.replace( '%content%', '<form><textarea id="'+self.id+'-editor" name="code"></textarea></form>' );
            }

            if ( self.options.form ) {
                self.popup = self.popup.replace( '%content%', '<div id="optionsframework"></div>' );
            }

            // Add to bottom of <body>. Will be removed
            // completely when modal is closed.
            $('body').append( self.popup );

            // Delete button?
            if ( self.options.button_delete ) {
                 $('#'+self.id+' .media-toolbar').prepend('<div class="media-toolbar-secondary"><a href="#" class="button media-button button-secondary button-large media-button-delete">'+self.options.button_delete+'</a></div>');
            }

            if ( self.options.code_editor ) {

                if ( self.$elem.is('.tb-content-block-code-link') ) {
                    var field_name = self.$elem.closest('.content-block').data('field-name');
                    content = self.$elem.closest('.content-block').find('textarea[name="'+field_name+'[content]"]').val();
                } else {
                    content = self.$elem.closest('.textarea-wrap').find('textarea').val();
                }

                $('#'+self.id+'-editor').val(content);

            } else {

                // Set marker to send content back to when modal
                // is closed.
                self.$target.after('<div id="'+self.id+'_marker"></div>');

                // Apend user content
                if ( self.options.form ) {
                    $('#'+self.id).find('#optionsframework').append( self.$target );
                } else {
                    $('#'+self.id).find('.content-mitt').append( self.$target );
                }

            }

        },

        display: function() {

            var self = this,
                height;

            // Disable scrolling on page behind modal
            $('body').addClass('themeblvd-stop-scroll');

            // Show modal
            self.$modal_window.show();

            // Setup code editor with CodeMirror
            if ( self.options.code_editor && typeof CodeMirror !== 'undefined'  ) {

                var mode;

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
                var editor = CodeMirror.fromTextArea(document.getElementById( self.id+'-editor'), {
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
                    $('#'+self.id+'-editor').data('CodeMirrorInstance', editor);
                }

            }

            // Adjust height if modal is not full size
            if ( this.options.size != 'large' ) {

                height = self.$modal_window.find('.media-frame-content-inner').outerHeight();
                height = 57 + height + 60; // 57 for title, 60 for footer

                if ( self.options.code_editor ) {
                    height += 2;
                } else {
                    height += 10;
                }

                self.$modal_window.find('.themeblvd-modal').css('max-height', height+'px');
            }

        },

        close: function() {

            var self = this;

            // Put content from modal back to
            // original location.
            if ( self.options.build ) {
                $('#'+self.id+'_marker').after( self.$target ).remove();
            }

            // Unbind links within modal
            self.$modal_window.find('.media-modal-close, .media-modal-backdrop').off('click');
            self.$modal_window.find('.media-button-insert').off('click');

            // Hide or Remove modal
            if ( self.options.build ) {
                self.$modal_window.remove();
            } else {
                self.$modal_window.hide();
            }

            // Allow page to be scrollable again
            $('body').removeClass('themeblvd-stop-scroll');

        },

        save: function() {

            var self = this;

            // If code editor, on save we can trasfer the code
            // from modal back to the textarea in the options.
            if ( self.options.code_editor ) {

                var editor = $('#'+self.id+'-editor').data('CodeMirrorInstance'),
                    content = editor.getValue(),
                    textarea;

                if ( self.$elem.is('.tb-content-block-code-link') ) {
                    var field_name = self.$elem.closest('.content-block').data('field-name');
                    textarea = self.$elem.closest('.content-block').find('textarea[name="'+field_name+'[content]"]');
                } else {
                    textarea = self.$elem.closest('.textarea-wrap').find('textarea');
                }

                textarea.val(content);

            }
        },

        delete_block: function() {

            var self = this,
                $column,
                column_content = '';

            // Cache column before we remove the widget
            $column = self.$elem.closest('.column-blocks');

            // Remove content block
            if ( self.$elem.parent().is('.content-block-nav') ) {
                self.$elem.closest('.content-block').remove();
            }

            // Add "mini-empty" class to column, if empty
            if ( ! $column.html().trim().length ) {
                $column.addClass('mini-empty');
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
        button_delete: '',              // Text for red button in lower left - if blank, no button.
        size: 'large',                  // Size of modal - small, medium, large
        build: true,                    // Whether to build the HTML markup for the popup
        form: false,                    // Whether this modal is a set of options
        code_editor: false,             // Whether this modal is a code editor
        code_lang: 'html',              // If code editor, code language -- html, css, javascript
        padding: false,                 // Size of modal - small, medium, large
        send_back: null,                // An object of something you want send to info back to, which you can utilize from callbacks
        on_load: function(modal){},     // Callback before modal window is displayed
        on_cancel: function(modal){},   // Callback when close button is clicked, before modal is closed
        on_save: function(modal){},     // Callback when save button is clicked, before modal is closed
        on_delete: function(modal){}    // Callback when save button is clicked, before modal is closed
    };

})(jQuery);