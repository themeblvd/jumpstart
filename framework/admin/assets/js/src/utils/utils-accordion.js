/**
 * Admin Utilities: Accordions
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 */
(function($, admin) {
  /**
   * Sets up an admin accordion.
   *
   * @since Theme_Blvd 2.7.0
   *
   * @param {object} element this
   */
  admin.accordion = function(element) {
    var $element = $(element);

    $element.find('.element-content').hide();

    $element.find('.element-content:first').show();

    $element.find('.element-trigger:first').addClass('active');

    $element.find('.element-trigger').on('click', function(event) {
      event.preventDefault();

      var $anchor = $(this);

      if (!$anchor.hasClass('active')) {
        $element.find('.element-content').hide();

        $element.find('.element-trigger').removeClass('active');

        $anchor.addClass('active');

        $anchor.next('.element-content').show();
      }
    });
  };
})(jQuery, window.themeblvd);
