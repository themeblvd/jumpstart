/**
 * Options System: General Setup
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
(function($, admin, l10n) {
  var $body = $('body');

  /**
   * Handles general option setup, called from the
   * jQuery `themeblvd` namespace.
   *
   * @since Theme_Blvd 2.7.0
   *
   * @param {object} element this
   */
  admin.options.setup = function(element) {
    var $element = $(element);

    /**
     * Option subgroup, show/hide checkbox.
     *
     * Within a subgroup with class `show-hide` a checkbox
     * with class `trigger` can toggle any options with
     * class `receiver` to show or hide, depending on if that
     * checkbox is checked.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.find('.show-hide').each(function() {
      var $subgroup = $(this),
        $checkbox = $subgroup.children('.trigger').find('input');

      if ($checkbox.is(':checked')) {
        $subgroup.children('.receiver').show();
      } else {
        $subgroup.find('.receiver').each(function() {
          $(this)
            .find('input, textarea, select')
            .prop('disabled', true);
        });

        $subgroup.children('.receiver').hide();
      }
    });

    /**
     * Option subgroup, show/hide <select> menu or radio
     * button group.
     *
     * Within a subgroup with class `show-hide-toggle` a
     * <select> menu or radio button group with class `trigger`
     * can toggle any options with class `receiver-{value}`
     * to show or hide, depending on selection.
     *
     * Note: All subgroup options that are NOT the trigger
     * must have both class `receiver` and `receiver-{value}`.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.find('.show-hide-toggle').each(function() {
      var $subgroup = $(this),
        $trigger = $subgroup.children('.trigger'),
        value = '';

      if ($trigger.hasClass('section-radio')) {
        value = $trigger.find('.of-radio:checked').val();
      } else {
        value = $trigger.find('.of-input').val();
      }

      $subgroup.children('.receiver').each(function() {
        $(this)
          .hide()
          .find('input, textarea, select')
          .prop('disabled', true);
      });

      $subgroup.children('.receiver-' + value).each(function() {
        $(this)
          .show()
          .find('input, textarea, select')
          .prop('disabled', false);
      });
    });

    /**
     * Option subgroup, show/hide descriptions.
     *
     * When multiple descriptions exist for options within
     * a `.desc-toggle` subgroup, they can be toggled
     * based on the selection of a `.trigger` option.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.find('.desc-toggle').each(function() {
      var $subgroup = $(this),
        value = $subgroup
          .children('.trigger')
          .find('.of-input')
          .val();

      $subgroup.find('.desc-receiver .explain').hide();

      $subgroup.find('.desc-receiver .explain.' + value).show();
    });

    /**
     * Sets up the type of content to populate a content
     * area.
     *
     * Used by theme to setup the content of a footer column
     * on theme options page.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.find('.section-content').each(function() {
      var $section = $(this),
        type = $section.find('.column-content-types select.select-type').val();

      $section.find('.column-content-type').hide();

      $section.find('.column-content-type-' + type).show();
    });

    /**
     * When configuring a group of columns, determines
     * the amount of columns that will show from the
     * user selection.
     *
     * Also, the number selected determines whether to show
     * the option to control columns widths.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.find('.columns').each(function() {
      var $select = $(this),
        num = $select.find('.select-col-num').val();

      if (num > 1) {
        $select.find('.select-wrap-grid').show();

        $select.find('.section-column_widths').show();

        $select
          .closest('.widget-content')
          .find('.column-height')
          .show();
      } else {
        $select.find('.select-wrap-grid').hide();

        $select.find('.section-column_widths').hide();

        $select
          .closest('.widget-content')
          .find('.column-height')
          .hide();
      }

      $select.find('.section-content').hide();

      for (var i = 1; i <= num; i++) {
        $select.find('.col_' + i).show();
      }
    });

    /**
     * Set up a `logo` type option.
     *
     * This option is meant specifically for setting up the
     * branding logo for a website and is used in the
     * framework's default theme options for the main site
     * logo.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.find('.section-logo').each(function() {
      var $parent = $(this),
        value = $parent.find('.select-type select').val();

      $parent.find('.logo-item').hide();

      $parent.find('.' + value).show();
    });

    /**
     * Within `typography` type option, allow user to toggle
     * open the Google and Typekit font inputs, based on the
     * type of font they've selected.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.find('.section-typography .of-typography-face').each(function() {
      var $select = $(this);

      $select
        .closest('.section-typography')
        .find('.google-font')
        .hide();

      $select
        .closest('.section-typography')
        .find('.typekit-font')
        .hide();

      $select
        .closest('.section-typography')
        .find('.' + $select.val() + '-font')
        .fadeIn('fast');
    });

    /**
     * Handles the `images` type option.
     *
     * This is basically just a radio button group, using
     * images to toggle the values of hidden radio inputs.
     */
    $element.find('.of-radio-img-label').hide();

    $element.find('.of-radio-img-img').show();

    $element.find('.of-radio-img-radio').hide();

    /**
     * Handles background configuration specifically when
     * the parallax option is supported and the
     * `background-attachment` option is changed.
     *
     * @since Theme_Blvd 2.5.0
     */
    $element.find('.select-parallax').each(function() {
      var $select = $(this),
        value = $select.find('.of-background-attachment').val();

      if ('parallax' === value) {
        $select.find('.tb-background-repeat').hide();

        $select.find('.tb-background-position').hide();

        $select.find('.tb-background-size').hide();

        $select.find('.parallax').show();
      } else {
        $select.find('.tb-background-repeat').show();

        $select.find('.tb-background-position').show();

        $select.find('.tb-background-size').show();

        $select.find('.parallax').hide();
      }
    });

    /**
     * Handles general background configuration when the
     * `background-repeat` option is changed.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.find('.of-background-properties').each(function() {
      var $select = $(this),
        repeatValue = $select.find('.of-background-repeat').val(),
        scrollingValue = $select.find('.of-background-attachment').val();

      if ('no-repeat' === repeatValue && 'parallax' !== scrollingValue) {
        $select.find('.of-background-size').show();
      } else {
        $select.find('.of-background-size').hide();
      }
    });

    /**
     * Sets up a `slide` type option.
     *
     * This refers specifically to the jQuery UI slider.
     *
     * @since Theme_Blvd 2.5.0
     */
    if ($.isFunction($.fn.slider)) {
      $element.find('.jquery-ui-slider').each(function() {
        var $slider = $(this),
          $input = $slider
            .closest('.jquery-ui-slider-wrap')
            .find('.slider-input'),
          units = $slider.data('units');

        $slider.slider({
          min: $slider.data('min'),
          max: $slider.data('max'),
          step: $slider.data('step'),
          value: parseInt($input.val()),
          create: function(event, ui) {
            $slider
              .find('.ui-slider-handle')
              .append(
                '<span class="display-value"><span class="display-value-text"></span><span class="display-value-arrow"></span></span>'
              );

            var $display = $slider.find('.display-value');

            $display.css('margin-left', '-' + $display.outerWidth() / 2 + 'px');

            $display.find('.display-value-text').text($input.val());
          },
          slide: function(event, ui) {
            $input.val(ui.value + units);

            $slider.find('.display-value-text').text(ui.value + units);
          }
        });
      });
    } // End check for $.fn.slider

    /**
     * Sets up a `color` type option.
     *
     * This refers specifically to WordPress built-in color
     * picker set up with $.fn.wpColorPicker.
     *
     * @since Theme_Blvd 2.5.0
     */
    if ($.isFunction($.fn.wpColorPicker)) {
      $element.find('.tb-color-picker').wpColorPicker({
        change: function(event, ui) {
          /*
           * Trigger custom event `themeblvd-color-change` that we
           * can bind to from options-bind.js.
           */
          $(event.target).trigger('themeblvd-color-change');
        }
      });
    } // End check for $.fn.wpColorPicker

    /**
     * Sets up a `button` type option and WordPress color
     * pickers within it.
     *
     * This refers specifically to WordPress built-in color
     * picker set up with $.fn.wpColorPicker.
     *
     * @since Theme_Blvd 2.5.0
     */
    if ($.isFunction($.fn.wpColorPicker)) {
      $element.find('.section-button').each(function() {
        var $parent = $(this);

        $parent.find('.color-picker').wpColorPicker();

        $parent.find('.color.bg .wp-color-result-text').text(l10n.bg_color);

        $parent
          .find('.color.bg_hover .wp-color-result-text')
          .text(l10n.bg_color_hover);

        $parent
          .find('.color.border .wp-color-result-text')
          .text(l10n.border_color);

        $parent.find('.color.text .wp-color-result-text').text(l10n.text_color);

        $parent
          .find('.color.text_hover .wp-color-result-text')
          .text(l10n.text_color_hover);

        // Show or hide the background color selection.
        if ($parent.find('.include.bg input').is(':checked')) {
          $parent.find('.color.bg').show();
        }

        // Show or hide the border color selection.
        if ($parent.find('.include.border input').is(':checked')) {
          $parent.find('.color.border').show();
        }
      });
    } // End check for $.fn.wpColorPicker

    /**
     * Sets up any form fields that require an icon
     * browser, for FontAwesome icon selection.
     *
     * @since Theme_Blvd 2.5.0
     */
    $element.find('.tb-input-icon-link').themeblvd('modal', null, {
      build: false,
      padding: true,
      size: 'custom', // Something other than `large` to trigger auto height.
      onDisplay: function(self) {
        var $input = self.$element,
          $browser = self.$modalWindow,
          icon = $input
            .closest('.input-wrap')
            .find('input')
            .val();

        // Reset icon browser.
        $browser.find('.media-frame-content').scrollTop(0);

        $browser.find('.icon-search-input').val('');

        $browser
          .find('.select-icon')
          .removeClass('selected')
          .show();

        $browser.find('.icon-selection-wrap .icon-preview').empty();

        // If valid icon exists in text input, apply the selection.
        if ($browser.find('[data-icon="' + icon + '"]').length > 0) {
          var $active = $browser.find('[data-icon="' + icon + '"]');

          $active.addClass('selected');

          $browser.find('.icon-selection').val(icon);

          $browser.find('.icon-preview').html($active.html());

          $browser.find('.icon-text-preview').html(icon);
        }
      },
      onSave: function(self) {
        var icon = self.$modalWindow.find('.icon-selection').val();

        // Send icon name back to input.
        self.$element
          .closest('.input-wrap')
          .find('input')
          .val(icon);
      }
    });

    /**
     * Sets up form fields that require the post ID
     * browser.
     *
     * @since Theme_Blvd 2.5.0
     */
    $element.find('.tb-input-post-id-link').themeblvd('modal', null, {
      build: false,
      padding: true,
      button: '',
      size: 'custom', // Something other than `large` to trigger auto height.
      onDisplay: function(self) {
        var $input = self.$element,
          $browser = self.$modalWindow;

        // Bind search ajax.
        $browser.find('#search-submit').off('click.tb-search-posts');

        $browser
          .find('#search-submit')
          .on('click.tb-search-posts', function(event) {
            event.preventDefault();

            var $search = $(this).closest('.post-browser-head'),
              data = {
                action: 'themeblvd_post_browser',
                data: $search.find('#post-search-input').val()
              };

            $search.find('.tb-loader').fadeIn(200);

            $.post(ajaxurl, data, function(response) {
              $browser
                .find('.ajax-mitt')
                .html('')
                .append(response);

              $search.find('.tb-loader').fadeOut(200);
            });
          });

        // Select a post and close modal.
        $browser.off('click.tb-select-post', '.select-post');

        $browser.on('click.tb-select-post', '.select-post', function(event) {
          event.preventDefault();

          $browser.find('#post-search-input').val('');

          $browser.find('.ajax-mitt').html('');

          $input
            .closest('.input-wrap')
            .find('.of-input')
            .val($(this).data('post-id'));

          self.close(self);
        });
      },
      onCancel: function(self) {
        $browser = self.$modalWindow;

        $browser.find('#post-search-input').val('');

        $browser.find('.ajax-mitt').html('');
      }
    });

    /**
     * Sets up form fields that require the texture browser,
     * to select a framework texture.
     *
     * @since Theme_Blvd 2.5.0
     */
    $element.find('.tb-texture-browser-link').themeblvd('modal', null, {
      build: false,
      padding: true,
      size: 'custom', // Something other than `large` to trigger auto height.
      onDisplay: function(self) {
        var $input = self.$element,
          $browser = self.$modalWindow,
          texture = $input
            .closest('.controls')
            .find('.of-input')
            .val();

        $browser.find('.media-frame-content').scrollTop(0);

        $browser.find('.select-texture').each(function() {
          $link = $(this);

          $link.removeClass('selected');

          if (texture === $link.data('texture')) {
            $link.addClass('selected');

            $browser.find('.texture-selection').val($link.data('texture'));

            $browser.find('.current-texture').text($link.data('texture-name'));
          }
        });
      },
      onSave: function(self) {
        var $select = self.$element.closest('.controls').find('.of-input'),
          texture = self.$modalWindow.find('.texture-selection').val();

        // Send texture back to original <select>.
        $select.val(texture);
      }
    });

    /**
     * Sets up footer sync option on theme options page.
     *
     * @since Theme_Blvd 2.5.0
     */
    if ($element.find('#tb-footer-sync').is(':checked')) {
      $element.find('.standard-footer-setup').hide();

      $element.find('.footer-template-setup').show();
    } else {
      $element.find('.standard-footer-setup').show();

      $element.find('.footer-template-setup').hide();
    }
  };
})(jQuery, window.themeblvd, themeblvdL10n);
