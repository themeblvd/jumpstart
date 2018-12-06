/**
 * Options System: General Event-Binding
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 */
(function($, admin) {
  /**
   * Handles general option binding, called from the
   * jQuery `themeblvd` namespace.
   *
   * @since Theme_Blvd 2.7.0
   *
   * @param {object} element this
   */
  admin.options.bind = function(element) {
    var $element = $(element),
      $body = $('body');

    /**
     * Bind modals by default to all links with `tb-modal-link`
     * class.
     *
     * @since Theme_Blvd 2.5.0
     */
    $element.find('.tb-modal-link').themeblvd('modal');

    /**
     * Set up tooltips.
     *
     * @since Theme_Blvd 2.5.0
     */
    if (!admin.isMobile($body)) {
      /**
       * Bind tooltips to all links with `tb-tooltip-link`
       * class.
       *
       * @since Theme_Blvd 2.5.0
       */
      $element.on('mouseenter', '.tb-tooltip-link', function() {
        var $link = $(this),
          position = $link.data('tooltip-position'),
          x = $link.offset().left,
          y = $link.offset().top,
          text = $link.data('tooltip-text'),
          markup =
            '<div class="themeblvd-tooltip %position%"> \
								   <div class="tooltip-inner"> \
									 %text% \
								   </div> \
								   <div class="tooltip-arrow"></div> \
								</div>';

        // Check for text toggle.
        if (!text && $link.data('tooltip-toggle')) {
          text = $link.data('tooltip-text-' + $link.data('tooltip-toggle'));
        }

        // If no text found at data-tooltip-text, then pull from title.
        if (!text) {
          text = $link.attr('title');
        }

        // If no position found at data-tooltip-position, set to "top".
        if (!position) {
          position = 'top';
        }

        // Setup markup
        markup = markup.replace('%position%', position);
        markup = markup.replace('%text%', text);

        // Append tooltip to page
        $body.append(markup);

        // Setup and display tooltip
        var $tooltip = $('.themeblvd-tooltip'),
          tooltip_height = $tooltip.outerHeight(),
          tooltip_width = $tooltip.outerWidth();

        // Position of tooltip relative to $link.
        switch (position) {
          case 'left':
            x = x - tooltip_width - 5; // 5px for arrow.
            y = y + 0.5 * $link.outerHeight();
            y = y - tooltip_height / 2;
            break;

          case 'right':
            x = x + $link.outerWidth() + 5; // 5px for arrow.
            y = y + 0.5 * $link.outerHeight();
            y = y - tooltip_height / 2;
            break;

          case 'bottom':
            x = x + 0.5 * $link.outerWidth();
            x = x - tooltip_width / 2;
            y = y + $link.outerHeight() + 2;
            break;

          case 'top':
          default:
            x = x + 0.5 * $link.outerWidth();
            x = x - tooltip_width / 2;
            y = y - tooltip_height - 2;
        }

        $tooltip
          .css({
            top: y + 'px',
            left: x + 'px'
          })
          .addClass('fade in');
      });

      $element.on('mouseleave', '.tb-tooltip-link', function() {
        $('.themeblvd-tooltip').remove();
      });

      /**
       * Remove any active tooltips if a link is clicked.
       *
       * @since Theme_Blvd 2.5.0
       */
      $element.find('.tb-tooltip-link').on('click', function() {
        var $link = $(this),
          toggle = $link.data('tooltip-toggle');

        $('.themeblvd-tooltip').remove();

        if (2 == toggle) {
          $link.data('tooltip-toggle', 1);
        } else {
          $link.data('tooltip-toggle', 2);
        }
      });
    } // End check for isMobile().

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
    $element.on('click', '.show-hide > .trigger input', function() {
      var $checkbox = $(this);

      if ($checkbox.is(':checked')) {
        $checkbox
          .closest('.show-hide')
          .find('.receiver')
          .each(function() {
            $(this)
              .find('input, textarea, select')
              .prop('disabled', false);
          });

        $checkbox
          .closest('.show-hide')
          .children('.receiver')
          .fadeIn('fast');
      } else {
        $checkbox
          .closest('.show-hide')
          .find('.receiver')
          .each(function() {
            $(this)
              .find('input, textarea, select')
              .prop('disabled', true);
          });

        $checkbox
          .closest('.show-hide')
          .children('.receiver')
          .hide();
      }
    });

    /**
     * Option subgroup, show/hide <select> menu.
     *
     * Within a subgroup with class `show-hide-toggle` a
     * <select> menu with class `trigger` can toggle any
     * options with class `receiver-{value}` to show or hide,
     * depending on selection.
     *
     * Note: All subgroup options that are NOT the trigger
     * must have both class `receiver` and `receiver-{value}`.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.on(
      'change',
      '.show-hide-toggle > .trigger select.of-input',
      function() {
        var $select = $(this),
          value = $select.val(),
          $subgroup = $select.closest('.show-hide-toggle');

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
      }
    );

    /**
     * Option subgroup, show/hide radio button group.
     *
     * Within a subgroup with class `show-hide-toggle` a
     * radio button set with class `trigger` can toggle any
     * options with class `receiver-{value}` to show or hide,
     * depending on selection.
     *
     * Note: All subgroup options that are NOT the trigger
     * must have both class `receiver` and `receiver-{value}`.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.on('click', '.show-hide-toggle > .trigger .of-radio', function() {
      var $radio = $(this),
        value = $radio.val(),
        $subgroup = $radio.closest('.show-hide-toggle');

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
    $element.on('change', '.desc-toggle > .trigger .of-input', function() {
      var $input = $(this),
        value = $input.val(),
        $subgroup = $input.closest('.desc-toggle');

      $subgroup.find('.desc-receiver .explain').hide();

      $subgroup.find('.desc-receiver .explain.' + value).show();
    });

    /**
     * Select the type of content to populate a content
     * area.
     *
     * Used by theme to setup the content of a footer column
     * on theme options page.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.on(
      'change',
      '.column-content-types select.select-type',
      function() {
        var $select = $(this),
          $section = $select.closest('.section-content'),
          type = $select.val();

        $section.find('.column-content-type').hide();

        $section.find('.column-content-type-' + type).show();
      }
    );

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
    $element.on('change', '.select-col-num', function() {
      var $select = $(this),
        num = $select.val(),
        $container = $select.closest('.columns');

      if (num > 1) {
        $container.find('.select-wrap-grid').show();

        $container.find('.section-column_widths').show();

        $container
          .closest('.widget-content')
          .find('.column-height')
          .show();
      } else {
        $container.find('.select-wrap-grid').hide();

        $container.find('.section-column_widths').hide();

        $container
          .closest('.widget-content')
          .find('.column-height')
          .hide();
      }

      $container.find('.section-content').hide();

      for (var i = 1; i <= num; i++) {
        $container.find('.col_' + i).show();
      }
    });

    /**
     * Handles a `logo` type option.
     *
     * This option is meant specifically for setting up the
     * branding logo for a website and is used in the
     * framework's default theme options for the main site
     * logo.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.on('change', '.section-logo .select-type select', function() {
      var $select = $(this),
        $parent = $select.closest('.section-logo'),
        value = $select.val();

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
    $element.on(
      'change',
      '.section-typography .of-typography-face',
      function() {
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
      }
    );

    /**
     * Handles the `images` type option.
     *
     * This is basically just a radio button group, using
     * images to toggle the values of hidden radio inputs.
     */
    $element
      .find('.tb-radio-img-img, .of-radio-img-img')
      .on('click', function(event) {
        event.preventDefault();

        var $img = $(this);

        $img
          .parent()
          .parent()
          .find('.of-radio-img-img')
          .removeClass('tb-radio-img-selected of-radio-img-selected');

        $img.addClass('tb-radio-img-selected of-radio-img-selected');
      });

    /**
     * Handles checkbox group of categories.
     *
     * When a user is presented with a checkbox group of
     * WordPress categories, they will have the selection
     * of "All" or individual categories.
     *
     * This script ensures that when "All" is selected
     * the other aren't, and vise versa.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.on('click', '.select-categories input', function() {
      var $current = $(this),
        $option = $current.closest('.controls');

      if ($current.prop('checked')) {
        if ($current.hasClass('all')) {
          $option.find('input').each(function() {
            var $checkbox = $(this);

            if (!$checkbox.hasClass('all')) {
              $checkbox.prop('checked', false);
            }
          });
        } else {
          $option.find('input.all').prop('checked', false);
        }
      }
    });

    /**
     * Handles background configuration specifically when
     * the parallax option is supported and the
     * `background-attachment` option is changed.
     *
     * @since Theme_Blvd 2.5.0
     */
    $element.on(
      'change',
      '.select-parallax .of-background-attachment',
      function() {
        var $select = $(this),
          $option = $select.closest('.select-parallax');

        if ('parallax' == $select.val()) {
          $option.find('.tb-background-repeat').hide();

          $option.find('.tb-background-position').hide();

          $option.find('.tb-background-size').hide();

          $option.find('.parallax').show();
        } else {
          $option.find('.tb-background-repeat').show();

          $option.find('.tb-background-position').show();

          $option.find('.tb-background-size').show();

          $option.find('.parallax').hide();
        }
      }
    );

    /**
     * Handles general background configuration when the
     * `background-repeat` option is changed.
     *
     * @since Theme_Blvd 2.2.0
     */
    $element.on(
      'change',
      '.of-background-properties .of-background-repeat',
      function() {
        var $select = $(this),
          $option = $select.closest('.of-background-properties'),
          repeatValue = $select.val(),
          scrollingValue = $option.find('.of-background-attachment').val();

        if ('no-repeat' === repeatValue && 'parallax' !== scrollingValue) {
          $option.find('.tb-background-size').show();
        } else {
          $option.find('.tb-background-size').hide();
        }
      }
    );

    /**
     * Handles the `button` option type.
     */
    $element.on('click', '.section-button .include input', function() {
      var $input = $(this),
        type = 'bg';

      if ($input.closest('.include').hasClass('border')) {
        type = 'border';
      }

      if ($input.is(':checked')) {
        $input
          .closest('.section-button')
          .find('.color.' + type)
          .show();
      } else {
        $input
          .closest('.section-button')
          .find('.color.' + type)
          .hide();
      }
    });

    /**
     * Handles footer sync checkbox on theme options
     * page.
     *
     * @since Theme_Blvd 2.5.0
     */
    $element.find('#tb-footer-sync').on('click', function() {
      if ($(this).is(':checked')) {
        $element.find('.standard-footer-setup').hide();

        $element.find('.footer-template-setup').show();
      } else {
        $element.find('.standard-footer-setup').show();

        $element.find('.footer-template-setup').hide();
      }
    });

    /**
     * Handles automated insertion of mape coordinates,
     * utilizng the Google Maps API.
     *
     * @since Theme_Blvd 2.5.0
     */
    if (typeof google === 'object' && typeof google.maps === 'object') {
      $element.on('click', '.section-geo .geo-insert-lat-long', function(
        event
      ) {
        event.preventDefault();

        var $link = $(this),
          $overlay = $link.closest('.geo-generate').find('.overlay'),
          geocoder = new google.maps.Geocoder(),
          address = $link.prev('.address').val(),
          latitude = '0',
          longitude = '0';

        $overlay.fadeIn(100);

        geocoder.geocode({ address: address }, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            latitude = results[0].geometry.location.lat();

            longitude = results[0].geometry.location.lng();
          }

          setTimeout(function() {
            $link
              .closest('.controls')
              .find('.geo-lat .geo-input')
              .val(latitude);

            $link
              .closest('.controls')
              .find('.geo-long .geo-input')
              .val(longitude);

            if (status != google.maps.GeocoderStatus.OK) {
              admin.confirm($link.data('oops'), { textOk: 'Ok' });
            } else {
              $link.prev('.address').val('');
            }

            $overlay.fadeOut(100);
          }, 1500);
        });
      });
    } // End check for Google Maps API.
  };
})(jQuery, window.themeblvd);
