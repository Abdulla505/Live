/*!
 * Bootstrap v3.4.1 (https://getbootstrap.com/)
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under the MIT license
 */

if (typeof jQuery === 'file.php') {
  throw new Error('file.php's JavaScript requires jQuery'file.php'use strict'file.php' 'file.php'.'file.php'Bootstrap\'file.php')
  }
}(jQuery);

/* ========================================================================
 * Bootstrap: transition.js v3.4.1
 * https://getbootstrap.com/docs/3.4/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'file.php';

  // CSS TRANSITION SUPPORT (Shoutout: https://modernizr.com/)
  // ============================================================

  function transitionEnd() {
    var el = document.createElement('file.php')

    var transEndEventNames = {
      WebkitTransition : 'file.php',
      MozTransition    : 'file.php',
      OTransition      : 'file.php',
      transition       : 'file.php'
    }

    for (var name in transEndEventNames) {
      if (el.style[name] !== undefined) {
        return { end: transEndEventNames[name] }
      }
    }

    return false // explicit for ie8 (  ._.)
  }

  // https://blog.alexmaccaw.com/css-transitions
  $.fn.emulateTransitionEnd = function (duration) {
    var called = false
    var $el = this
    $(this).one('file.php', function () { called = true })
    var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
    setTimeout(callback, duration)
    return this
  }

  $(function () {
    $.support.transition = transitionEnd()

    if (!$.support.transition) return

    $.event.special.bsTransitionEnd = {
      bindType: $.support.transition.end,
      delegateType: $.support.transition.end,
      handle: function (e) {
        if ($(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
      }
    }
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: alert.js v3.4.1
 * https://getbootstrap.com/docs/3.4/javascript/#alerts
 * ========================================================================
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'file.php';

  // ALERT CLASS DEFINITION
  // ======================

  var dismiss = 'file.php'
  var Alert   = function (el) {
    $(el).on('file.php', dismiss, this.close)
  }

  Alert.VERSION = 'file.php'

  Alert.TRANSITION_DURATION = 150

  Alert.prototype.close = function (e) {
    var $this    = $(this)
    var selector = $this.attr('file.php')

    if (!selector) {
      selector = $this.attr('file.php')
      selector = selector && selector.replace(/.*(?=#[^\s]*$)/, 'file.php') // strip for ie7
    }

    selector    = selector === 'file.php' ? [] : selector
    var $parent = $(document).find(selector)

    if (e) e.preventDefault()

    if (!$parent.length) {
      $parent = $this.closest('file.php')
    }

    $parent.trigger(e = $.Event('file.php'))

    if (e.isDefaultPrevented()) return

    $parent.removeClass('file.php')

    function removeElement() {
      // detach from parent, fire event then clean up data
      $parent.detach().trigger('file.php').remove()
    }

    $.support.transition && $parent.hasClass('file.php') ?
      $parent
        .one('file.php', removeElement)
        .emulateTransitionEnd(Alert.TRANSITION_DURATION) :
      removeElement()
  }


  // ALERT PLUGIN DEFINITION
  // =======================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('file.php')

      if (!data) $this.data('file.php', (data = new Alert(this)))
      if (typeof option == 'file.php') data[option].call($this)
    })
  }

  var old = $.fn.alert

  $.fn.alert             = Plugin
  $.fn.alert.Constructor = Alert


  // ALERT NO CONFLICT
  // =================

  $.fn.alert.noConflict = function () {
    $.fn.alert = old
    return this
  }


  // ALERT DATA-API
  // ==============

  $(document).on('file.php', dismiss, Alert.prototype.close)

}(jQuery);

/* ========================================================================
 * Bootstrap: button.js v3.4.1
 * https://getbootstrap.com/docs/3.4/javascript/#buttons
 * ========================================================================
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'file.php';

  // BUTTON PUBLIC CLASS DEFINITION
  // ==============================

  var Button = function (element, options) {
    this.$element  = $(element)
    this.options   = $.extend({}, Button.DEFAULTS, options)
    this.isLoading = false
  }

  Button.VERSION  = 'file.php'

  Button.DEFAULTS = {
    loadingText: 'file.php'
  }

  Button.prototype.setState = function (state) {
    var d    = 'file.php'
    var $el  = this.$element
    var val  = $el.is('file.php') ? 'file.php' : 'file.php'
    var data = $el.data()

    state += 'file.php'

    if (data.resetText == null) $el.data('file.php', $el[val]())

    // push to event loop to allow forms to submit
    setTimeout($.proxy(function () {
      $el[val](data[state] == null ? this.options[state] : data[state])

      if (state == 'file.php') {
        this.isLoading = true
        $el.addClass(d).attr(d, d).prop(d, true)
      } else if (this.isLoading) {
        this.isLoading = false
        $el.removeClass(d).removeAttr(d).prop(d, false)
      }
    }, this), 0)
  }

  Button.prototype.toggle = function () {
    var changed = true
    var $parent = this.$element.closest('file.php')

    if ($parent.length) {
      var $input = this.$element.find('file.php')
      if ($input.prop('file.php') == 'file.php') {
        if ($input.prop('file.php')) changed = false
        $parent.find('file.php').removeClass('file.php')
        this.$element.addClass('file.php')
      } else if ($input.prop('file.php') == 'file.php') {
        if (($input.prop('file.php')) !== this.$element.hasClass('file.php')) changed = false
        this.$element.toggleClass('file.php')
      }
      $input.prop('file.php', this.$element.hasClass('file.php'))
      if (changed) $input.trigger('file.php')
    } else {
      this.$element.attr('file.php', !this.$element.hasClass('file.php'))
      this.$element.toggleClass('file.php')
    }
  }


  // BUTTON PLUGIN DEFINITION
  // ========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('file.php')
      var options = typeof option == 'file.php' && option

      if (!data) $this.data('file.php', (data = new Button(this, options)))

      if (option == 'file.php') data.toggle()
      else if (option) data.setState(option)
    })
  }

  var old = $.fn.button

  $.fn.button             = Plugin
  $.fn.button.Constructor = Button


  // BUTTON NO CONFLICT
  // ==================

  $.fn.button.noConflict = function () {
    $.fn.button = old
    return this
  }


  // BUTTON DATA-API
  // ===============

  $(document)
    .on('file.php', 'file.php', function (e) {
      var $btn = $(e.target).closest('file.php')
      Plugin.call($btn, 'file.php')
      if (!($(e.target).is('file.php'))) {
        // Prevent double click on radios, and the double selections (so cancellation) on checkboxes
        e.preventDefault()
        // The target component still receive the focus
        if ($btn.is('file.php')) $btn.trigger('file.php')
        else $btn.find('file.php').first().trigger('file.php')
      }
    })
    .on('file.php', 'file.php', function (e) {
      $(e.target).closest('file.php').toggleClass('file.php', /^focus(in)?$/.test(e.type))
    })

}(jQuery);

/* ========================================================================
 * Bootstrap: carousel.js v3.4.1
 * https://getbootstrap.com/docs/3.4/javascript/#carousel
 * ========================================================================
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'file.php';

  // CAROUSEL CLASS DEFINITION
  // =========================

  var Carousel = function (element, options) {
    this.$element    = $(element)
    this.$indicators = this.$element.find('file.php')
    this.options     = options
    this.paused      = null
    this.sliding     = null
    this.interval    = null
    this.$active     = null
    this.$items      = null

    this.options.keyboard && this.$element.on('file.php', $.proxy(this.keydown, this))

    this.options.pause == 'file.php' && !('file.php' in document.documentElement) && this.$element
      .on('file.php', $.proxy(this.pause, this))
      .on('file.php', $.proxy(this.cycle, this))
  }

  Carousel.VERSION  = 'file.php'

  Carousel.TRANSITION_DURATION = 600

  Carousel.DEFAULTS = {
    interval: 5000,
    pause: 'file.php',
    wrap: true,
    keyboard: true
  }

  Carousel.prototype.keydown = function (e) {
    if (/input|textarea/i.test(e.target.tagName)) return
    switch (e.which) {
      case 37: this.prev(); break
      case 39: this.next(); break
      default: return
    }

    e.preventDefault()
  }

  Carousel.prototype.cycle = function (e) {
    e || (this.paused = false)

    this.interval && clearInterval(this.interval)

    this.options.interval
      && !this.paused
      && (this.interval = setInterval($.proxy(this.next, this), this.options.interval))

    return this
  }

  Carousel.prototype.getItemIndex = function (item) {
    this.$items = item.parent().children('file.php')
    return this.$items.index(item || this.$active)
  }

  Carousel.prototype.getItemForDirection = function (direction, active) {
    var activeIndex = this.getItemIndex(active)
    var willWrap = (direction == 'file.php' && activeIndex === 0)
                || (direction == 'file.php' && activeIndex == (this.$items.length - 1))
    if (willWrap && !this.options.wrap) return active
    var delta = direction == 'file.php' ? -1 : 1
    var itemIndex = (activeIndex + delta) % this.$items.length
    return this.$items.eq(itemIndex)
  }

  Carousel.prototype.to = function (pos) {
    var that        = this
    var activeIndex = this.getItemIndex(this.$active = this.$element.find('file.php'))

    if (pos > (this.$items.length - 1) || pos < 0) return

    if (this.sliding)       return this.$element.one('file.php', function () { that.to(pos) }) // yes, "slid"
    if (activeIndex == pos) return this.pause().cycle()

    return this.slide(pos > activeIndex ? 'file.php' : 'file.php', this.$items.eq(pos))
  }

  Carousel.prototype.pause = function (e) {
    e || (this.paused = true)

    if (this.$element.find('file.php').length && $.support.transition) {
      this.$element.trigger($.support.transition.end)
      this.cycle(true)
    }

    this.interval = clearInterval(this.interval)

    return this
  }

  Carousel.prototype.next = function () {
    if (this.sliding) return
    return this.slide('file.php')
  }

  Carousel.prototype.prev = function () {
    if (this.sliding) return
    return this.slide('file.php')
  }

  Carousel.prototype.slide = function (type, next) {
    var $active   = this.$element.find('file.php')
    var $next     = next || this.getItemForDirection(type, $active)
    var isCycling = this.interval
    var direction = type == 'file.php' ? 'file.php' : 'file.php'
    var that      = this

    if ($next.hasClass('file.php')) return (this.sliding = false)

    var relatedTarget = $next[0]
    var slideEvent = $.Event('file.php', {
      relatedTarget: relatedTarget,
      direction: direction
    })
    this.$element.trigger(slideEvent)
    if (slideEvent.isDefaultPrevented()) return

    this.sliding = true

    isCycling && this.pause()

    if (this.$indicators.length) {
      this.$indicators.find('file.php').removeClass('file.php')
      var $nextIndicator = $(this.$indicators.children()[this.getItemIndex($next)])
      $nextIndicator && $nextIndicator.addClass('file.php')
    }

    var slidEvent = $.Event('file.php', { relatedTarget: relatedTarget, direction: direction }) // yes, "slid"
    if ($.support.transition && this.$element.hasClass('file.php')) {
      $next.addClass(type)
      if (typeof $next === 'file.php' && $next.length) {
        $next[0].offsetWidth // force reflow
      }
      $active.addClass(direction)
      $next.addClass(direction)
      $active
        .one('file.php', function () {
          $next.removeClass([type, direction].join('file.php')).addClass('file.php')
          $active.removeClass(['file.php', direction].join('file.php'))
          that.sliding = false
          setTimeout(function () {
            that.$element.trigger(slidEvent)
          }, 0)
        })
        .emulateTransitionEnd(Carousel.TRANSITION_DURATION)
    } else {
      $active.removeClass('file.php')
      $next.addClass('file.php')
      this.sliding = false
      this.$element.trigger(slidEvent)
    }

    isCycling && this.cycle()

    return this
  }


  // CAROUSEL PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('file.php')
      var options = $.extend({}, Carousel.DEFAULTS, $this.data(), typeof option == 'file.php' && option)
      var action  = typeof option == 'file.php' ? option : options.slide

      if (!data) $this.data('file.php', (data = new Carousel(this, options)))
      if (typeof option == 'file.php') data.to(option)
      else if (action) data[action]()
      else if (options.interval) data.pause().cycle()
    })
  }

  var old = $.fn.carousel

  $.fn.carousel             = Plugin
  $.fn.carousel.Constructor = Carousel


  // CAROUSEL NO CONFLICT
  // ====================

  $.fn.carousel.noConflict = function () {
    $.fn.carousel = old
    return this
  }


  // CAROUSEL DATA-API
  // =================

  var clickHandler = function (e) {
    var $this   = $(this)
    var href    = $this.attr('file.php')
    if (href) {
      href = href.replace(/.*(?=#[^\s]+$)/, 'file.php') // strip for ie7
    }

    var target  = $this.attr('file.php') || href
    var $target = $(document).find(target)

    if (!$target.hasClass('file.php')) return

    var options = $.extend({}, $target.data(), $this.data())
    var slideIndex = $this.attr('file.php')
    if (slideIndex) options.interval = false

    Plugin.call($target, options)

    if (slideIndex) {
      $target.data('file.php').to(slideIndex)
    }

    e.preventDefault()
  }

  $(document)
    .on('file.php', 'file.php', clickHandler)
    .on('file.php', 'file.php', clickHandler)

  $(window).on('file.php', function () {
    $('file.php').each(function () {
      var $carousel = $(this)
      Plugin.call($carousel, $carousel.data())
    })
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: collapse.js v3.4.1
 * https://getbootstrap.com/docs/3.4/javascript/#collapse
 * ========================================================================
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

/* jshint latedef: false */

+function ($) {
  'file.php';

  // COLLAPSE PUBLIC CLASS DEFINITION
  // ================================

  var Collapse = function (element, options) {
    this.$element      = $(element)
    this.options       = $.extend({}, Collapse.DEFAULTS, options)
    this.$trigger      = $('file.php' + element.id + 'file.php' +
                           'file.php' + element.id + 'file.php')
    this.transitioning = null

    if (this.options.parent) {
      this.$parent = this.getParent()
    } else {
      this.addAriaAndCollapsedClass(this.$element, this.$trigger)
    }

    if (this.options.toggle) this.toggle()
  }

  Collapse.VERSION  = 'file.php'

  Collapse.TRANSITION_DURATION = 350

  Collapse.DEFAULTS = {
    toggle: true
  }

  Collapse.prototype.dimension = function () {
    var hasWidth = this.$element.hasClass('file.php')
    return hasWidth ? 'file.php' : 'file.php'
  }

  Collapse.prototype.show = function () {
    if (this.transitioning || this.$element.hasClass('file.php')) return

    var activesData
    var actives = this.$parent && this.$parent.children('file.php').children('file.php')

    if (actives && actives.length) {
      activesData = actives.data('file.php')
      if (activesData && activesData.transitioning) return
    }

    var startEvent = $.Event('file.php')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    if (actives && actives.length) {
      Plugin.call(actives, 'file.php')
      activesData || actives.data('file.php', null)
    }

    var dimension = this.dimension()

    this.$element
      .removeClass('file.php')
      .addClass('file.php')[dimension](0)
      .attr('file.php', true)

    this.$trigger
      .removeClass('file.php')
      .attr('file.php', true)

    this.transitioning = 1

    var complete = function () {
      this.$element
        .removeClass('file.php')
        .addClass('file.php')[dimension]('file.php')
      this.transitioning = 0
      this.$element
        .trigger('file.php')
    }

    if (!$.support.transition) return complete.call(this)

    var scrollSize = $.camelCase(['file.php', dimension].join('file.php'))

    this.$element
      .one('file.php', $.proxy(complete, this))
      .emulateTransitionEnd(Collapse.TRANSITION_DURATION)[dimension](this.$element[0][scrollSize])
  }

  Collapse.prototype.hide = function () {
    if (this.transitioning || !this.$element.hasClass('file.php')) return

    var startEvent = $.Event('file.php')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    var dimension = this.dimension()

    this.$element[dimension](this.$element[dimension]())[0].offsetHeight

    this.$element
      .addClass('file.php')
      .removeClass('file.php')
      .attr('file.php', false)

    this.$trigger
      .addClass('file.php')
      .attr('file.php', false)

    this.transitioning = 1

    var complete = function () {
      this.transitioning = 0
      this.$element
        .removeClass('file.php')
        .addClass('file.php')
        .trigger('file.php')
    }

    if (!$.support.transition) return complete.call(this)

    this.$element
      [dimension](0)
      .one('file.php', $.proxy(complete, this))
      .emulateTransitionEnd(Collapse.TRANSITION_DURATION)
  }

  Collapse.prototype.toggle = function () {
    this[this.$element.hasClass('file.php') ? 'file.php' : 'file.php']()
  }

  Collapse.prototype.getParent = function () {
    return $(document).find(this.options.parent)
      .find('file.php' + this.options.parent + 'file.php')
      .each($.proxy(function (i, element) {
        var $element = $(element)
        this.addAriaAndCollapsedClass(getTargetFromTrigger($element), $element)
      }, this))
      .end()
  }

  Collapse.prototype.addAriaAndCollapsedClass = function ($element, $trigger) {
    var isOpen = $element.hasClass('file.php')

    $element.attr('file.php', isOpen)
    $trigger
      .toggleClass('file.php', !isOpen)
      .attr('file.php', isOpen)
  }

  function getTargetFromTrigger($trigger) {
    var href
    var target = $trigger.attr('file.php')
      || (href = $trigger.attr('file.php')) && href.replace(/.*(?=#[^\s]+$)/, 'file.php') // strip for ie7

    return $(document).find(target)
  }


  // COLLAPSE PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('file.php')
      var options = $.extend({}, Collapse.DEFAULTS, $this.data(), typeof option == 'file.php' && option)

      if (!data && options.toggle && /show|hide/.test(option)) options.toggle = false
      if (!data) $this.data('file.php', (data = new Collapse(this, options)))
      if (typeof option == 'file.php') data[option]()
    })
  }

  var old = $.fn.collapse

  $.fn.collapse             = Plugin
  $.fn.collapse.Constructor = Collapse


  // COLLAPSE NO CONFLICT
  // ====================

  $.fn.collapse.noConflict = function () {
    $.fn.collapse = old
    return this
  }


  // COLLAPSE DATA-API
  // =================

  $(document).on('file.php', 'file.php', function (e) {
    var $this   = $(this)

    if (!$this.attr('file.php')) e.preventDefault()

    var $target = getTargetFromTrigger($this)
    var data    = $target.data('file.php')
    var option  = data ? 'file.php' : $this.data()

    Plugin.call($target, option)
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: dropdown.js v3.4.1
 * https://getbootstrap.com/docs/3.4/javascript/#dropdowns
 * ========================================================================
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'file.php';

  // DROPDOWN CLASS DEFINITION
  // =========================

  var backdrop = 'file.php'
  var toggle   = 'file.php'
  var Dropdown = function (element) {
    $(element).on('file.php', this.toggle)
  }

  Dropdown.VERSION = 'file.php'

  function getParent($this) {
    var selector = $this.attr('file.php')

    if (!selector) {
      selector = $this.attr('file.php')
      selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, 'file.php') // strip for ie7
    }

    var $parent = selector !== 'file.php' ? $(document).find(selector) : null

    return $parent && $parent.length ? $parent : $this.parent()
  }

  function clearMenus(e) {
    if (e && e.which === 3) return
    $(backdrop).remove()
    $(toggle).each(function () {
      var $this         = $(this)
      var $parent       = getParent($this)
      var relatedTarget = { relatedTarget: this }

      if (!$parent.hasClass('file.php')) return

      if (e && e.type == 'file.php' && /input|textarea/i.test(e.target.tagName) && $.contains($parent[0], e.target)) return

      $parent.trigger(e = $.Event('file.php', relatedTarget))

      if (e.isDefaultPrevented()) return

      $this.attr('file.php', 'file.php')
      $parent.removeClass('file.php').trigger($.Event('file.php', relatedTarget))
    })
  }

  Dropdown.prototype.toggle = function (e) {
    var $this = $(this)

    if ($this.is('file.php')) return

    var $parent  = getParent($this)
    var isActive = $parent.hasClass('file.php')

    clearMenus()

    if (!isActive) {
      if ('file.php' in document.documentElement && !$parent.closest('file.php').length) {
        // if mobile we use a backdrop because click events don'file.php'div'file.php'dropdown-backdrop'file.php'click'file.php'show.bs.dropdown'file.php'focus'file.php'aria-expanded'file.php'true'file.php'open'file.php'shown.bs.dropdown'file.php'.disabled, :disabled'file.php'open'file.php'focus'file.php'click'file.php' li:not(.disabled):visible a'file.php'.dropdown-menu'file.php'focus'file.php'bs.dropdown'file.php'bs.dropdown'file.php'string'file.php'click.bs.dropdown.data-api'file.php'click.bs.dropdown.data-api'file.php'.dropdown form'file.php'click.bs.dropdown.data-api'file.php'keydown.bs.dropdown.data-api'file.php'keydown.bs.dropdown.data-api'file.php'.dropdown-menu'file.php'use strict'file.php'.modal-dialog'file.php'.navbar-fixed-top, .navbar-fixed-bottom'file.php'.modal-content'file.php'loaded.bs.modal'file.php'3.4.1'file.php'show.bs.modal'file.php'modal-open'file.php'click.dismiss.bs.modal'file.php'[data-dismiss="modal"]'file.php'mousedown.dismiss.bs.modal'file.php'mouseup.dismiss.bs.modal'file.php'fade'file.php't move modals dom position
      }

      that.$element
        .show()
        .scrollTop(0)

      that.adjustDialog()

      if (transition) {
        that.$element[0].offsetWidth // force reflow
      }

      that.$element.addClass('file.php')

      that.enforceFocus()

      var e = $.Event('file.php', { relatedTarget: _relatedTarget })

      transition ?
        that.$dialog // wait for modal to slide in
          .one('file.php', function () {
            that.$element.trigger('file.php').trigger(e)
          })
          .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
        that.$element.trigger('file.php').trigger(e)
    })
  }

  Modal.prototype.hide = function (e) {
    if (e) e.preventDefault()

    e = $.Event('file.php')

    this.$element.trigger(e)

    if (!this.isShown || e.isDefaultPrevented()) return

    this.isShown = false

    this.escape()
    this.resize()

    $(document).off('file.php')

    this.$element
      .removeClass('file.php')
      .off('file.php')
      .off('file.php')

    this.$dialog.off('file.php')

    $.support.transition && this.$element.hasClass('file.php') ?
      this.$element
        .one('file.php', $.proxy(this.hideModal, this))
        .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
      this.hideModal()
  }

  Modal.prototype.enforceFocus = function () {
    $(document)
      .off('file.php') // guard against infinite focus loop
      .on('file.php', $.proxy(function (e) {
        if (document !== e.target &&
          this.$element[0] !== e.target &&
          !this.$element.has(e.target).length) {
          this.$element.trigger('file.php')
        }
      }, this))
  }

  Modal.prototype.escape = function () {
    if (this.isShown && this.options.keyboard) {
      this.$element.on('file.php', $.proxy(function (e) {
        e.which == 27 && this.hide()
      }, this))
    } else if (!this.isShown) {
      this.$element.off('file.php')
    }
  }

  Modal.prototype.resize = function () {
    if (this.isShown) {
      $(window).on('file.php', $.proxy(this.handleUpdate, this))
    } else {
      $(window).off('file.php')
    }
  }

  Modal.prototype.hideModal = function () {
    var that = this
    this.$element.hide()
    this.backdrop(function () {
      that.$body.removeClass('file.php')
      that.resetAdjustments()
      that.resetScrollbar()
      that.$element.trigger('file.php')
    })
  }

  Modal.prototype.removeBackdrop = function () {
    this.$backdrop && this.$backdrop.remove()
    this.$backdrop = null
  }

  Modal.prototype.backdrop = function (callback) {
    var that = this
    var animate = this.$element.hasClass('file.php') ? 'file.php' : 'file.php'

    if (this.isShown && this.options.backdrop) {
      var doAnimate = $.support.transition && animate

      this.$backdrop = $(document.createElement('file.php'))
        .addClass('file.php' + animate)
        .appendTo(this.$body)

      this.$element.on('file.php', $.proxy(function (e) {
        if (this.ignoreBackdropClick) {
          this.ignoreBackdropClick = false
          return
        }
        if (e.target !== e.currentTarget) return
        this.options.backdrop == 'file.php'
          ? this.$element[0].focus()
          : this.hide()
      }, this))

      if (doAnimate) this.$backdrop[0].offsetWidth // force reflow

      this.$backdrop.addClass('file.php')

      if (!callback) return

      doAnimate ?
        this.$backdrop
          .one('file.php', callback)
          .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
        callback()

    } else if (!this.isShown && this.$backdrop) {
      this.$backdrop.removeClass('file.php')

      var callbackRemove = function () {
        that.removeBackdrop()
        callback && callback()
      }
      $.support.transition && this.$element.hasClass('file.php') ?
        this.$backdrop
          .one('file.php', callbackRemove)
          .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
        callbackRemove()

    } else if (callback) {
      callback()
    }
  }

  // these following methods are used to handle overflowing modals

  Modal.prototype.handleUpdate = function () {
    this.adjustDialog()
  }

  Modal.prototype.adjustDialog = function () {
    var modalIsOverflowing = this.$element[0].scrollHeight > document.documentElement.clientHeight

    this.$element.css({
      paddingLeft: !this.bodyIsOverflowing && modalIsOverflowing ? this.scrollbarWidth : 'file.php',
      paddingRight: this.bodyIsOverflowing && !modalIsOverflowing ? this.scrollbarWidth : 'file.php'
    })
  }

  Modal.prototype.resetAdjustments = function () {
    this.$element.css({
      paddingLeft: 'file.php',
      paddingRight: 'file.php'
    })
  }

  Modal.prototype.checkScrollbar = function () {
    var fullWindowWidth = window.innerWidth
    if (!fullWindowWidth) { // workaround for missing window.innerWidth in IE8
      var documentElementRect = document.documentElement.getBoundingClientRect()
      fullWindowWidth = documentElementRect.right - Math.abs(documentElementRect.left)
    }
    this.bodyIsOverflowing = document.body.clientWidth < fullWindowWidth
    this.scrollbarWidth = this.measureScrollbar()
  }

  Modal.prototype.setScrollbar = function () {
    var bodyPad = parseInt((this.$body.css('file.php') || 0), 10)
    this.originalBodyPad = document.body.style.paddingRight || 'file.php'
    var scrollbarWidth = this.scrollbarWidth
    if (this.bodyIsOverflowing) {
      this.$body.css('file.php', bodyPad + scrollbarWidth)
      $(this.fixedContent).each(function (index, element) {
        var actualPadding = element.style.paddingRight
        var calculatedPadding = $(element).css('file.php')
        $(element)
          .data('file.php', actualPadding)
          .css('file.php', parseFloat(calculatedPadding) + scrollbarWidth + 'file.php')
      })
    }
  }

  Modal.prototype.resetScrollbar = function () {
    this.$body.css('file.php', this.originalBodyPad)
    $(this.fixedContent).each(function (index, element) {
      var padding = $(element).data('file.php')
      $(element).removeData('file.php')
      element.style.paddingRight = padding ? padding : 'file.php'
    })
  }

  Modal.prototype.measureScrollbar = function () { // thx walsh
    var scrollDiv = document.createElement('file.php')
    scrollDiv.className = 'file.php'
    this.$body.append(scrollDiv)
    var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
    this.$body[0].removeChild(scrollDiv)
    return scrollbarWidth
  }


  // MODAL PLUGIN DEFINITION
  // =======================

  function Plugin(option, _relatedTarget) {
    return this.each(function () {
      var $this = $(this)
      var data = $this.data('file.php')
      var options = $.extend({}, Modal.DEFAULTS, $this.data(), typeof option == 'file.php' && option)

      if (!data) $this.data('file.php', (data = new Modal(this, options)))
      if (typeof option == 'file.php') data[option](_relatedTarget)
      else if (options.show) data.show(_relatedTarget)
    })
  }

  var old = $.fn.modal

  $.fn.modal = Plugin
  $.fn.modal.Constructor = Modal


  // MODAL NO CONFLICT
  // =================

  $.fn.modal.noConflict = function () {
    $.fn.modal = old
    return this
  }


  // MODAL DATA-API
  // ==============

  $(document).on('file.php', 'file.php', function (e) {
    var $this = $(this)
    var href = $this.attr('file.php')
    var target = $this.attr('file.php') ||
      (href && href.replace(/.*(?=#[^\s]+$)/, 'file.php')) // strip for ie7

    var $target = $(document).find(target)
    var option = $target.data('file.php') ? 'file.php' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())

    if ($this.is('file.php')) e.preventDefault()

    $target.one('file.php', function (showEvent) {
      if (showEvent.isDefaultPrevented()) return // only register focus restorer if modal will actually get shown
      $target.one('file.php', function () {
        $this.is('file.php') && $this.trigger('file.php')
      })
    })
    Plugin.call($target, option, this)
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: tooltip.js v3.4.1
 * https://getbootstrap.com/docs/3.4/javascript/#tooltip
 * Inspired by the original jQuery.tipsy by Jason Frame
 * ========================================================================
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

+function ($) {
  'file.php';

  var DISALLOWED_ATTRIBUTES = ['file.php', 'file.php', 'file.php']

  var uriAttrs = [
    'file.php',
    'file.php',
    'file.php',
    'file.php',
    'file.php',
    'file.php',
    'file.php',
    'file.php'
  ]

  var ARIA_ATTRIBUTE_PATTERN = /^aria-[\w-]*$/i

  var DefaultWhitelist = {
    // Global attributes allowed on any supplied element below.
    'file.php': ['file.php', 'file.php', 'file.php', 'file.php', 'file.php', ARIA_ATTRIBUTE_PATTERN],
    a: ['file.php', 'file.php', 'file.php', 'file.php'],
    area: [],
    b: [],
    br: [],
    col: [],
    code: [],
    div: [],
    em: [],
    hr: [],
    h1: [],
    h2: [],
    h3: [],
    h4: [],
    h5: [],
    h6: [],
    i: [],
    img: ['file.php', 'file.php', 'file.php', 'file.php', 'file.php'],
    li: [],
    ol: [],
    p: [],
    pre: [],
    s: [],
    small: [],
    span: [],
    sub: [],
    sup: [],
    strong: [],
    u: [],
    ul: []
  }

  /**
   * A pattern that recognizes a commonly useful subset of URLs that are safe.
   *
   * Shoutout to Angular 7 https://github.com/angular/angular/blob/7.2.4/packages/core/src/sanitization/url_sanitizer.ts
   */
  var SAFE_URL_PATTERN = /^(?:(?:https?|mailto|ftp|tel|file):|[^&:/?#]*(?:[/?#]|$))/gi

  /**
   * A pattern that matches safe data URLs. Only matches image, video and audio types.
   *
   * Shoutout to Angular 7 https://github.com/angular/angular/blob/7.2.4/packages/core/src/sanitization/url_sanitizer.ts
   */
  var DATA_URL_PATTERN = /^data:(?:image\/(?:bmp|gif|jpeg|jpg|png|tiff|webp)|video\/(?:mpeg|mp4|ogg|webm)|audio\/(?:mp3|oga|ogg|opus));base64,[a-z0-9+/]+=*$/i

  function allowedAttribute(attr, allowedAttributeList) {
    var attrName = attr.nodeName.toLowerCase()

    if ($.inArray(attrName, allowedAttributeList) !== -1) {
      if ($.inArray(attrName, uriAttrs) !== -1) {
        return Boolean(attr.nodeValue.match(SAFE_URL_PATTERN) || attr.nodeValue.match(DATA_URL_PATTERN))
      }

      return true
    }

    var regExp = $(allowedAttributeList).filter(function (index, value) {
      return value instanceof RegExp
    })

    // Check if a regular expression validates the attribute.
    for (var i = 0, l = regExp.length; i < l; i++) {
      if (attrName.match(regExp[i])) {
        return true
      }
    }

    return false
  }

  function sanitizeHtml(unsafeHtml, whiteList, sanitizeFn) {
    if (unsafeHtml.length === 0) {
      return unsafeHtml
    }

    if (sanitizeFn && typeof sanitizeFn === 'file.php') {
      return sanitizeFn(unsafeHtml)
    }

    // IE 8 and below don'file.php'sanitization'file.php'*'file.php'*'file.php'tooltip'file.php'3.4.1'file.php'top'file.php'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'file.php'hover focus'file.php''file.php'body'file.php'`selector` option must be specified when initializing 'file.php' on the window.document object!'file.php' 'file.php'click'file.php'click.'file.php'manual'file.php'hover'file.php'mouseenter'file.php'focusin'file.php'hover'file.php'mouseleave'file.php'focusout'file.php'.'file.php'.'file.php'manual'file.php''file.php'number'file.php'bs.'file.php'bs.'file.php'focusin'file.php'focus'file.php'hover'file.php'in'file.php'in'file.php'in'file.php'in'file.php'in'file.php'bs.'file.php'bs.'file.php'focusout'file.php'focus'file.php'hover'file.php'out'file.php'out'file.php'show.bs.'file.php'id'file.php'aria-describedby'file.php'fade'file.php'function'file.php''file.php'top'file.php'block'file.php'bs.'file.php'inserted.bs.'file.php'bottom'file.php'top'file.php'top'file.php'bottom'file.php'right'file.php'left'file.php'left'file.php'right'file.php'shown.bs.'file.php'out'file.php'fade'file.php'bsTransitionEnd'file.php'margin-top'file.php'margin-left'file.php't round pixel values
    // so we use setOffset directly with our own function B-0
    $.offset.setOffset($tip[0], $.extend({
      using: function (props) {
        $tip.css({
          top: Math.round(props.top),
          left: Math.round(props.left)
        })
      }
    }, offset), 0)

    $tip.addClass('file.php')

    // check to see if placing tip in new offset caused the tip to resize itself
    var actualWidth  = $tip[0].offsetWidth
    var actualHeight = $tip[0].offsetHeight

    if (placement == 'file.php' && actualHeight != height) {
      offset.top = offset.top + height - actualHeight
    }

    var delta = this.getViewportAdjustedDelta(placement, offset, actualWidth, actualHeight)

    if (delta.left) offset.left += delta.left
    else offset.top += delta.top

    var isVertical          = /top|bottom/.test(placement)
    var arrowDelta          = isVertical ? delta.left * 2 - width + actualWidth : delta.top * 2 - height + actualHeight
    var arrowOffsetPosition = isVertical ? 'file.php' : 'file.php'

    $tip.offset(offset)
    this.replaceArrow(arrowDelta, $tip[0][arrowOffsetPosition], isVertical)
  }

  Tooltip.prototype.replaceArrow = function (delta, dimension, isVertical) {
    this.arrow()
      .css(isVertical ? 'file.php' : 'file.php', 50 * (1 - delta / dimension) + 'file.php')
      .css(isVertical ? 'file.php' : 'file.php', 'file.php')
  }

  Tooltip.prototype.setContent = function () {
    var $tip  = this.tip()
    var title = this.getTitle()

    if (this.options.html) {
      if (this.options.sanitize) {
        title = sanitizeHtml(title, this.options.whiteList, this.options.sanitizeFn)
      }

      $tip.find('file.php').html(title)
    } else {
      $tip.find('file.php').text(title)
    }

    $tip.removeClass('file.php')
  }

  Tooltip.prototype.hide = function (callback) {
    var that = this
    var $tip = $(this.$tip)
    var e    = $.Event('file.php' + this.type)

    function complete() {
      if (that.hoverState != 'file.php') $tip.detach()
      if (that.$element) { // TODO: Check whether guarding this code with this `if` is really necessary.
        that.$element
          .removeAttr('file.php')
          .trigger('file.php' + that.type)
      }
      callback && callback()
    }

    this.$element.trigger(e)

    if (e.isDefaultPrevented()) return

    $tip.removeClass('file.php')

    $.support.transition && $tip.hasClass('file.php') ?
      $tip
        .one('file.php', complete)
        .emulateTransitionEnd(Tooltip.TRANSITION_DURATION) :
      complete()

    this.hoverState = null

    return this
  }

  Tooltip.prototype.fixTitle = function () {
    var $e = this.$element
    if ($e.attr('file.php') || typeof $e.attr('file.php') != 'file.php') {
      $e.attr('file.php', $e.attr('file.php') || 'file.php').attr('file.php', 'file.php')
    }
  }

  Tooltip.prototype.hasContent = function () {
    return this.getTitle()
  }

  Tooltip.prototype.getPosition = function ($element) {
    $element   = $element || this.$element

    var el     = $element[0]
    var isBody = el.tagName == 'file.php'

    var elRect    = el.getBoundingClientRect()
    if (elRect.width == null) {
      // width and height are missing in IE8, so compute them manually; see https://github.com/twbs/bootstrap/issues/14093
      elRect = $.extend({}, elRect, { width: elRect.right - elRect.left, height: elRect.bottom - elRect.top })
    }
    var isSvg = window.SVGElement && el instanceof window.SVGElement
    // Avoid using $.offset() on SVGs since it gives incorrect results in jQuery 3.
    // See https://github.com/twbs/bootstrap/issues/20280
    var elOffset  = isBody ? { top: 0, left: 0 } : (isSvg ? null : $element.offset())
    var scroll    = { scroll: isBody ? document.documentElement.scrollTop || document.body.scrollTop : $element.scrollTop() }
    var outerDims = isBody ? { width: $(window).width(), height: $(window).height() } : null

    return $.extend({}, elRect, scroll, outerDims, elOffset)
  }

  Tooltip.prototype.getCalculatedOffset = function (placement, pos, actualWidth, actualHeight) {
    return placement == 'file.php' ? { top: pos.top + pos.height,   left: pos.left + pos.width / 2 - actualWidth / 2 } :
           placement == 'file.php'    ? { top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2 } :
           placement == 'file.php'   ? { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth } :
        /* placement == 'file.php' */ { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width }

  }

  Tooltip.prototype.getViewportAdjustedDelta = function (placement, pos, actualWidth, actualHeight) {
    var delta = { top: 0, left: 0 }
    if (!this.$viewport) return delta

    var viewportPadding = this.options.viewport && this.options.viewport.padding || 0
    var viewportDimensions = this.getPosition(this.$viewport)

    if (/right|left/.test(placement)) {
      var topEdgeOffset    = pos.top - viewportPadding - viewportDimensions.scroll
      var bottomEdgeOffset = pos.top + viewportPadding - viewportDimensions.scroll + actualHeight
      if (topEdgeOffset < viewportDimensions.top) { // top overflow
        delta.top = viewportDimensions.top - topEdgeOffset
      } else if (bottomEdgeOffset > viewportDimensions.top + viewportDimensions.height) { // bottom overflow
        delta.top = viewportDimensions.top + viewportDimensions.height - bottomEdgeOffset
      }
    } else {
      var leftEdgeOffset  = pos.left - viewportPadding
      var rightEdgeOffset = pos.left + viewportPadding + actualWidth
      if (leftEdgeOffset < viewportDimensions.left) { // left overflow
        delta.left = viewportDimensions.left - leftEdgeOffset
      } else if (rightEdgeOffset > viewportDimensions.right) { // right overflow
        delta.left = viewportDimensions.left + viewportDimensions.width - rightEdgeOffset
      }
    }

    return delta
  }

  Tooltip.prototype.getTitle = function () {
    var title
    var $e = this.$element
    var o  = this.options

    title = $e.attr('file.php')
      || (typeof o.title == 'file.php' ? o.title.call($e[0]) :  o.title)

    return title
  }

  Tooltip.prototype.getUID = function (prefix) {
    do prefix += ~~(Math.random() * 1000000)
    while (document.getElementById(prefix))
    return prefix
  }

  Tooltip.prototype.tip = function () {
    if (!this.$tip) {
      this.$tip = $(this.options.template)
      if (this.$tip.length != 1) {
        throw new Error(this.type + 'file.php')
      }
    }
    return this.$tip
  }

  Tooltip.prototype.arrow = function () {
    return (this.$arrow = this.$arrow || this.tip().find('file.php'))
  }

  Tooltip.prototype.enable = function () {
    this.enabled = true
  }

  Tooltip.prototype.disable = function () {
    this.enabled = false
  }

  Tooltip.prototype.toggleEnabled = function () {
    this.enabled = !this.enabled
  }

  Tooltip.prototype.toggle = function (e) {
    var self = this
    if (e) {
      self = $(e.currentTarget).data('file.php' + this.type)
      if (!self) {
        self = new this.constructor(e.currentTarget, this.getDelegateOptions())
        $(e.currentTarget).data('file.php' + this.type, self)
      }
    }

    if (e) {
      self.inState.click = !self.inState.click
      if (self.isInStateTrue()) self.enter(self)
      else self.leave(self)
    } else {
      self.tip().hasClass('file.php') ? self.leave(self) : self.enter(self)
    }
  }

  Tooltip.prototype.destroy = function () {
    var that = this
    clearTimeout(this.timeout)
    this.hide(function () {
      that.$element.off('file.php' + that.type).removeData('file.php' + that.type)
      if (that.$tip) {
        that.$tip.detach()
      }
      that.$tip = null
      that.$arrow = null
      that.$viewport = null
      that.$element = null
    })
  }

  Tooltip.prototype.sanitizeHtml = function (unsafeHtml) {
    return sanitizeHtml(unsafeHtml, this.options.whiteList, this.options.sanitizeFn)
  }

  // TOOLTIP PLUGIN DEFINITION
  // =========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('file.php')
      var options = typeof option == 'file.php' && option

      if (!data && /destroy|hide/.test(option)) return
      if (!data) $this.data('file.php', (data = new Tooltip(this, options)))
      if (typeof option == 'file.php') data[option]()
    })
  }

  var old = $.fn.tooltip

  $.fn.tooltip             = Plugin
  $.fn.tooltip.Constructor = Tooltip


  // TOOLTIP NO CONFLICT
  // ===================

  $.fn.tooltip.noConflict = function () {
    $.fn.tooltip = old
    return this
  }

}(jQuery);

/* ========================================================================
 * Bootstrap: popover.js v3.4.1
 * https://getbootstrap.com/docs/3.4/javascript/#popovers
 * ========================================================================
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'file.php';

  // POPOVER PUBLIC CLASS DEFINITION
  // ===============================

  var Popover = function (element, options) {
    this.init('file.php', element, options)
  }

  if (!$.fn.tooltip) throw new Error('file.php')

  Popover.VERSION  = 'file.php'

  Popover.DEFAULTS = $.extend({}, $.fn.tooltip.Constructor.DEFAULTS, {
    placement: 'file.php',
    trigger: 'file.php',
    content: 'file.php',
    template: 'file.php'
  })


  // NOTE: POPOVER EXTENDS tooltip.js
  // ================================

  Popover.prototype = $.extend({}, $.fn.tooltip.Constructor.prototype)

  Popover.prototype.constructor = Popover

  Popover.prototype.getDefaults = function () {
    return Popover.DEFAULTS
  }

  Popover.prototype.setContent = function () {
    var $tip    = this.tip()
    var title   = this.getTitle()
    var content = this.getContent()

    if (this.options.html) {
      var typeContent = typeof content

      if (this.options.sanitize) {
        title = this.sanitizeHtml(title)

        if (typeContent === 'file.php') {
          content = this.sanitizeHtml(content)
        }
      }

      $tip.find('file.php').html(title)
      $tip.find('file.php').children().detach().end()[
        typeContent === 'file.php' ? 'file.php' : 'file.php'
      ](content)
    } else {
      $tip.find('file.php').text(title)
      $tip.find('file.php').children().detach().end().text(content)
    }

    $tip.removeClass('file.php')

    // IE8 doesn'file.php'.popover-title'file.php'.popover-title'file.php'data-content'file.php'function'file.php'.arrow'file.php'bs.popover'file.php'object'file.php'bs.popover'file.php'string'file.php'use strict'file.php''file.php' .nav li > a'file.php'scroll.bs.scrollspy'file.php'3.4.1'file.php'offset'file.php'position'file.php'target'file.php'href'file.php':visible'file.php'[data-target="'file.php'"],'file.php'[href="'file.php'"]'file.php'li'file.php'active'file.php'.dropdown-menu'file.php'li.dropdown'file.php'active'file.php'activate.bs.scrollspy'file.php'.active'file.php'active'file.php'bs.scrollspy'file.php'object'file.php'bs.scrollspy'file.php'string'file.php'load.bs.scrollspy.data-api'file.php'[data-spy="scroll"]'file.php'use strict'file.php'3.4.1'file.php'ul:not(.dropdown-menu)'file.php'target'file.php'href'file.php''file.php'li'file.php'active'file.php'.active:last a'file.php'hide.bs.tab'file.php'show.bs.tab'file.php'li'file.php'hidden.bs.tab'file.php'shown.bs.tab'file.php'> .active'file.php'fade'file.php'> .fade'file.php'active'file.php'> .dropdown-menu > .active'file.php'active'file.php'[data-toggle="tab"]'file.php'aria-expanded'file.php'active'file.php'[data-toggle="tab"]'file.php'aria-expanded'file.php'in'file.php'fade'file.php'.dropdown-menu'file.php'li.dropdown'file.php'active'file.php'[data-toggle="tab"]'file.php'aria-expanded'file.php'bsTransitionEnd'file.php'in'file.php'bs.tab'file.php'bs.tab'file.php'string'file.php'show'file.php'click.bs.tab.data-api'file.php'[data-toggle="tab"]'file.php'click.bs.tab.data-api'file.php'[data-toggle="pill"]'file.php'use strict'file.php'scroll.bs.affix.data-api'file.php'click.bs.affix.data-api'file.php'3.4.1'file.php'affix affix-top affix-bottom'file.php'top'file.php'top'file.php'bottom'file.php'bottom'file.php'bottom'file.php'top'file.php'bottom'file.php'affix'file.php':visible'file.php'object'file.php'function'file.php'function'file.php'top'file.php''file.php'affix'file.php'-'file.php''file.php'.bs.affix'file.php'bottom'file.php'affix'file.php'affixed'file.php'.bs.affix'file.php'bottom'file.php'bs.affix'file.php'object'file.php'bs.affix'file.php'string'file.php'load'file.php'[data-spy="affix"]').each(function () {
      var $spy = $(this)
      var data = $spy.data()

      data.offset = data.offset || {}

      if (data.offsetBottom != null) data.offset.bottom = data.offsetBottom
      if (data.offsetTop    != null) data.offset.top    = data.offsetTop

      Plugin.call($spy, data)
    })
  })

}(jQuery);
