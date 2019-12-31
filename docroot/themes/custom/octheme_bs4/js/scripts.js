/**
 * @file
 * Behaviors for the octheme_bs4 theme.
 */

(function ($, _, Drupal, drupalSettings) {
  'use strict';

  Drupal.behaviors.octheme_bs4 = {
    attach: function (context) {

      // octheme_bs4 JavaScript behaviors goes here.
    }
  };


  $(document).ready(function() {
    // find the first container and mark it.
    //$('.mayo_accordian_container').first().addClass('mayoactive');
    //any containters that are not marked, hide the content
    //$('.mayo_accordian_container:not(.mayoactive)').find('.mayo_accordion_content_sm').hide();
    //make the title act like a button to toggle content
    $('.mayo_accordian_title').click(function(e) {
      e.preventDefault(); // prevent the default action
      e.stopPropagation(); // stop the clmayo_accordian_containerick from bubbling
// marked is displayed. If not marked, then mark it and open it up.
      if(!$(this).parent().hasClass('mayoactive')) {
        //$('.mayo_accordian_container').removeClass('mayoactive');
        $(this).parent().addClass('mayoactive');
        //$('.mayo_accordian_container').find('.mayo_accordion_content_sm').slideUp();
        $(this).next().slideDown();}
      else  {
        $(this).parent().removeClass('mayoactive');
        $(this).next().slideUp();
      }
    });
  });

  Drupal.behaviors.hideEmptyPanes = {
    attach: function (context, settings) {
      $('#main-wrapper .panel-pane, .pane-pane-oa-form-actions', context).each(function () {
        // Look for empty panels that also don't contain input fields
        // (input fields don't generate text()
        if ($(this).find('input,img,.geofieldMap').length == 0 && $(this).text().trim().length == 0) {
          $(this).css('display', 'none');
        }
      });
      // Check the edit panel at bottom AFTER we have done the above hiding
      $('.pane-oa-edit-minipanel .radix-layouts-content', context).each(function () {
        var text = $(this).find('.panel-pane:visible').text();
        if (text.trim().length == 0) {
          $(this).css('display', 'none');
        }
      });
      $('.oa-responsive-regions-toggle-left', context).each(function () {
        $(this).bind('click', function() {
          $('.navbar-tray').removeClass('active');
        });
      });
    }
  };
})(window.jQuery, window._, window.Drupal, window.drupalSettings);
