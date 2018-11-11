/**
 * Admin Utilities: Confirmation Prompt
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
(function($, admin, l10n) {
  /**
   * Sets up confirmation prompt.
   *
   * @since Theme_Blvd 2.7.0
   *
   * @param {string}      message         Message to user in confirmation prompt.
   * @param {object}      args            Options for confirmation popup.
   * @param {bool}        args.confirm    Whether to include Ok and Cancel buttons.
   * @param {bool}        args.verify     Whether to include Yes and No buttons.
   * @param {bool|string} args.input      Text input (can be true or string for default input value).
   * @param {string}      args.inputDesc  Description for below input.
   * @param {string}      args.textOk     Ok button text.
   * @param {string}      args.textCancel Cancel button text.
   * @param {string}      args.textYes    Yes button text.
   * @param {string}      args.textNo     No button text.
   * @param {string}      args.class      Optional CSS class to add to prompt.
   * @param {function}    callback        Callback function once prompt is clicked.
   */
  admin.confirm = function(message, args, callback) {
    var $body = $('body'),
      $window = $(window),
      $outer,
      $inner,
      $buttons;

    args = $.extend(
      {
        confirm: false,
        verify: false,
        input: false,
        inputDesc: '',
        textOk: l10n.ok,
        textCancel: l10n.cancel,
        textYes: l10n.yes,
        textNo: l10n.no,
        className: ''
      },
      args
    );

    if (args.input_desc) {
      // Backwards compatibility.
      args.inputDesc = args.input_desc;
    }

    // Append initial output.

    $body.append('<div class="themeblvd-confirm-overlay"></div>');

    $body.append('<div class="themeblvd-confirm-outer"></div>');

    $outer = $('.themeblvd-confirm-outer');

    $outer.append('<div class="themeblvd-confirm-inner"></div>');

    $inner = $('.themeblvd-confirm-inner');

    $inner.append(message);

    $outer.css(
      'left',
      ($window.width() - $outer.width()) / 2 + $window.scrollLeft() + 'px'
    );

    $outer.css('top', '100px').fadeIn(200);

    // Add optional CSS class.

    if (args.className) {
      $outer.addClass(args.className);
    }

    // Append text input.

    if (args.input) {
      if ('string' == typeof args.input) {
        $inner.append(
          '<div class="themeblvd-confirm-input"><input type="text" class="themeblvd-confirm-text-box" t="themeblvd-confirm-text-box" value="' +
            args.input +
            '" /></div>'
        );
      } else if ('object' == typeof args.input) {
        $inner.append(
          $('<div class="themeblvd-confirm-input"></div>').append(args.input)
        );
      } else {
        $inner.append(
          '<div class="themeblvd-confirm-input"><input type="text" class="themeblvd-confirm-text-box" /></div>'
        );
      }

      if (args.inputDesc) {
        $outer
          .find('.themeblvd-confirm-text-box')
          .after('<label>' + args.inputDesc + '</label>');
      }

      $outer.find('.themeblvd-confirm-text-box').focus();
    }

    // Append buttons.

    $inner.append('<div class="themeblvd-confirm-buttons"></div>');

    $buttons = $('.themeblvd-confirm-buttons');

    if (args.confirm || args.input) {
      $buttons.append(
        '<button class="button-primary" value="ok">' + args.textOk + '</button>'
      );

      $buttons.append(
        '<button class="button-secondary" value="cancel">' +
          args.textCancel +
          '</button>'
      );
    } else if (args.verify) {
      $buttons.append(
        '<button class="button-primary" value="ok">' +
          args.textYes +
          '</button>'
      );

      $buttons.append(
        '<button class="button-secondary" value="cancel">' +
          args.textNo +
          '</button>'
      );
    } else {
      $buttons.append(
        '<button class="button-primary" value="ok">' + args.textOk + '</button>'
      );
    }

    $(document).on('keydown', function(event) {
      if ($('.themeblvd-confirm-overlay').is(':visible')) {
        if (13 == event.keyCode) {
          $('.themeblvd-confirm-buttons > button[value="ok"]').trigger('click');
        }

        if (27 == event.keyCode) {
          $('.themeblvd-confirm-buttons > button[value="cancel"]').trigger(
            'click'
          );
        }
      }
    });

    var inputText = $('.themeblvd-confirm-text-box').val();

    $('.themeblvd-confirm-text-box').on('keyup', function() {
      inputText = $(this).val();
    });

    $buttons.find('button').on('click', function() {
      $('.themeblvd-confirm-overlay').remove();

      $outer.remove();

      if (callback) {
        var buttonType = $(this).attr('value');

        if ('ok' == buttonType) {
          if (args.input) {
            callback(inputText);
          } else {
            callback(true);
          }
        } else if ('cancel' == buttonType) {
          callback(false);
        }
      }
    });
  };
})(jQuery, window.themeblvd, themeblvdL10n);

/**
 * Confirmation prompt backwards compatibility.
 *
 * We're phasing out tbc_confirm() function in the
 * process of adding all WP admin functionality to
 * window.themeblvd.
 *
 * But since other plugins still may be using tbc_confirm(),
 * we'll keep it as a wrapper for now.
 *
 * @since Theme_Blvd 2.0.0
 * @deprecated Theme_Blvd 2.7.0
 */
function tbc_confirm(message, args, callback) {
  window.themeblvd.confirm(message, args, callback);
}
