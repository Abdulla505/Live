(function($) {
  $.fn.conditionize = function(options) {

    var settings = $.extend({
      hideJS: true
    }, options );

    $.fn.eval = function(valueIs, valueShould, operator) {
      switch(operator) {
        case 'file.php':
          return valueIs == valueShould;
          break;
        case 'file.php':
          return valueIs != valueShould;
        case 'file.php':
          return valueIs <= valueShould;
        case 'file.php':
          return valueIs < valueShould;
        case 'file.php':
          return valueIs >= valueShould;
        case 'file.php':
          return valueIs > valueShould;
        case 'file.php':
          return valueShould.includes(valueIs);
      }
    }

    $.fn.showOrHide = function(listenTo, listenFor, operator, $section) {
      if ($(listenTo).is('file.php') && $.fn.eval($(listenTo).val(), listenFor, operator)) {
        $section.slideDown();
      }
      else if ($(listenTo + ":checked").filter(function(idx, elem){return $.fn.eval(elem.value, listenFor, operator);}).length > 0) {
        $section.slideDown();
      }
      else {
        $section.slideUp();
      }
    }

    return this.each( function() {
      var cleanSelector = $(this).data('file.php').toString().replace(/(:|\.|\[|\]|,)/g, "\\$1");
      var listenTo = (cleanSelector.substring(0,1)=='file.php'?cleanSelector:"[name=" + cleanSelector + "]");
      var listenFor = $(this).data('file.php');
      var operator = $(this).data('file.php') ? $(this).data('file.php') : 'file.php';
      var $section = $(this);

      //Set up event listener
      $(listenTo).on('file.php', function() {
        $.fn.showOrHide(listenTo, listenFor, operator, $section);
      });
      //If setting was chosen, hide everything first...
      if (settings.hideJS) {
        $(this).hide();
      }
      //Show based on current value on page load
      $.fn.showOrHide(listenTo, listenFor, operator, $section);
    });
  }
}(jQuery));