/**
 * Admin Utilities: General UI Components
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 */
(function($, admin) {
  /**
   * Sets up the general UI components called from
   * the jQuery `themeblvd` namespace.
   *
   * @since Theme_Blvd 2.7.0
   *
   * @param {object} element this
   */
  admin.setup = function(element) {
    var $element = $(element);

    /**
     * Toggle admin UI sidebar widgets.
     *
     * @since Theme_Blvd 2.0.0
     */
    $element.off('click.tb-widget');

    $element.on(
      'click.tb-widget',
      '.widget-name-arrow, .block-widget-name-arrow',
      function(event) {
        event.preventDefault();

        var $button = $(this),
          type = 'widget',
          closed = false;

        if ($button.hasClass('block-widget-name-arrow')) {
          type = 'block-widget';
        }

        if (
          $button.closest('.' + type + '-name').hasClass(type + '-name-closed')
        ) {
          closed = true;
        }

        if (closed) {
          // Show widget.

          $button
            .closest('.' + type)
            .find('.' + type + '-content')
            .show();

          $button
            .closest('.' + type)
            .find('.' + type + '-name')
            .removeClass(type + '-name-closed');

          // Refresh any code editor options.
          $button.closest('.' + type).themeblvd('options', 'code-editor');
        } else {
          // Close widget.

          $button
            .closest('.' + type)
            .find('.' + type + '-content')
            .hide();

          $button
            .closest('.' + type)
            .find('.' + type + '-name')
            .addClass(type + '-name-closed');
        }
      }
    );

    /**
     * Setup help tooltips.
     *
     * @since Theme_Blvd 2.0.0
     */
    $element.on('click', '.tooltip-link', function(event) {
      event.preventDefault();

      admin.confirm($(this).attr('title'), { textOk: 'Ok' });
    });

    /**
     * Delete item by HTML ID passed through
     * link's href.
     *
     * @since Theme_Blvd 2.0.0
     */
    $element.on('click', '.delete-me', function(event) {
      var $link = $(this),
        item = $link.attr('href');

      admin.confirm($link.attr('title'), { confirm: true }, function(response) {
        if (response) {
          $(item).remove();
        }
      });
    });
  };
})(jQuery, window.themeblvd);
