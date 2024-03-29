/*!
 * imagesLoaded PACKAGED v4.1.4
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

!function(e,t){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",t):"object"==typeof module&&module.exports?module.exports=t():e.EvEmitter=t()}("undefined"!=typeof window?window:this,function(){function e(){}var t=e.prototype;return t.on=function(e,t){if(e&&t){var i=this._events=this._events||{},n=i[e]=i[e]||[];return n.indexOf(t)==-1&&n.push(t),this}},t.once=function(e,t){if(e&&t){this.on(e,t);var i=this._onceEvents=this._onceEvents||{},n=i[e]=i[e]||{};return n[t]=!0,this}},t.off=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){var n=i.indexOf(t);return n!=-1&&i.splice(n,1),this}},t.emitEvent=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){i=i.slice(0),t=t||[];for(var n=this._onceEvents&&this._onceEvents[e],o=0;o<i.length;o++){var r=i[o],s=n&&n[r];s&&(this.off(e,r),delete n[r]),r.apply(this,t)}return this}},t.allOff=function(){delete this._events,delete this._onceEvents},e}),function(e,t){"use strict";"function"==typeof define&&define.amd?define(["ev-emitter/ev-emitter"],function(i){return t(e,i)}):"object"==typeof module&&module.exports?module.exports=t(e,require("ev-emitter")):e.imagesLoaded=t(e,e.EvEmitter)}("undefined"!=typeof window?window:this,function(e,t){function i(e,t){for(var i in t)e[i]=t[i];return e}function n(e){if(Array.isArray(e))return e;var t="object"==typeof e&&"number"==typeof e.length;return t?d.call(e):[e]}function o(e,t,r){if(!(this instanceof o))return new o(e,t,r);var s=e;return"string"==typeof e&&(s=document.querySelectorAll(e)),s?(this.elements=n(s),this.options=i({},this.options),"function"==typeof t?r=t:i(this.options,t),r&&this.on("always",r),this.getImages(),h&&(this.jqDeferred=new h.Deferred),void setTimeout(this.check.bind(this))):void a.error("Bad element for imagesLoaded "+(s||e))}function r(e){this.img=e}function s(e,t){this.url=e,this.element=t,this.img=new Image}var h=e.jQuery,a=e.console,d=Array.prototype.slice;o.prototype=Object.create(t.prototype),o.prototype.options={},o.prototype.getImages=function(){this.images=[],this.elements.forEach(this.addElementImages,this)},o.prototype.addElementImages=function(e){"IMG"==e.nodeName&&this.addImage(e),this.options.background===!0&&this.addElementBackgroundImages(e);var t=e.nodeType;if(t&&u[t]){for(var i=e.querySelectorAll("img"),n=0;n<i.length;n++){var o=i[n];this.addImage(o)}if("string"==typeof this.options.background){var r=e.querySelectorAll(this.options.background);for(n=0;n<r.length;n++){var s=r[n];this.addElementBackgroundImages(s)}}}};var u={1:!0,9:!0,11:!0};return o.prototype.addElementBackgroundImages=function(e){var t=getComputedStyle(e);if(t)for(var i=/url\((['"])?(.*?)\1\)/gi,n=i.exec(t.backgroundImage);null!==n;){var o=n&&n[2];o&&this.addBackground(o,e),n=i.exec(t.backgroundImage)}},o.prototype.addImage=function(e){var t=new r(e);this.images.push(t)},o.prototype.addBackground=function(e,t){var i=new s(e,t);this.images.push(i)},o.prototype.check=function(){function e(e,i,n){setTimeout(function(){t.progress(e,i,n)})}var t=this;return this.progressedCount=0,this.hasAnyBroken=!1,this.images.length?void this.images.forEach(function(t){t.once("progress",e),t.check()}):void this.complete()},o.prototype.progress=function(e,t,i){this.progressedCount++,this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded,this.emitEvent("progress",[this,e,t]),this.jqDeferred&&this.jqDeferred.notify&&this.jqDeferred.notify(this,e),this.progressedCount==this.images.length&&this.complete(),this.options.debug&&a&&a.log("progress: "+i,e,t)},o.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emitEvent(e,[this]),this.emitEvent("always",[this]),this.jqDeferred){var t=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[t](this)}},r.prototype=Object.create(t.prototype),r.prototype.check=function(){var e=this.getIsImageComplete();return e?void this.confirm(0!==this.img.naturalWidth,"naturalWidth"):(this.proxyImage=new Image,this.proxyImage.addEventListener("load",this),this.proxyImage.addEventListener("error",this),this.img.addEventListener("load",this),this.img.addEventListener("error",this),void(this.proxyImage.src=this.img.src))},r.prototype.getIsImageComplete=function(){return this.img.complete&&this.img.naturalWidth},r.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.img,t])},r.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},r.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindEvents()},r.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindEvents()},r.prototype.unbindEvents=function(){this.proxyImage.removeEventListener("load",this),this.proxyImage.removeEventListener("error",this),this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype=Object.create(r.prototype),s.prototype.check=function(){this.img.addEventListener("load",this),this.img.addEventListener("error",this),this.img.src=this.url;var e=this.getIsImageComplete();e&&(this.confirm(0!==this.img.naturalWidth,"naturalWidth"),this.unbindEvents())},s.prototype.unbindEvents=function(){this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.element,t])},o.makeJQueryPlugin=function(t){t=t||e.jQuery,t&&(h=t,h.fn.imagesLoaded=function(e,t){var i=new o(this,e,t);return i.jqDeferred.promise(h(this))})},o.makeJQueryPlugin(),o});

/**
 * UpSolution Theme Core JavaScript Code
 *
 * @requires jQuery
 */
if ( window.$us === undefined ) {
	window.$us = {};
}

/**
 * Retrieve/set/erase dom modificator class <mod>_<value> for UpSolution CSS Framework
 * @param {String} mod Modificator namespace
 * @param {String} [value] Value
 * @returns {string|jQuery}
 */
jQuery.fn.usMod = function( mod, value ) {
	if ( this.length == 0 ) {
		return this;
	}
	// Remove class modificator
	if ( value === false ) {
		this.get( 0 ).className = this.get( 0 ).className.replace( new RegExp( '(^| )' + mod + '\_[a-zA-Z0-9\_\-]+( |$)' ), '$2' );
		return this;
	}
	var pcre = new RegExp( '^.*?' + mod + '\_([a-zA-Z0-9\_\-]+).*?$' ),
		arr;
	// Retrieve modificator
	if ( value === undefined ) {
		return ( arr = pcre.exec( this.get( 0 ).className ) ) ? arr[ 1 ] : false;
	}
	// Set modificator
	else {
		this.usMod( mod, false ).get( 0 ).className += ' ' + mod + '_' + value;
		return this;
	}
};

/**
 * Convert data from PHP to boolean the right way
 * @param {mixed} value
 * @returns {Boolean}
 */
$us.toBool = function( value ) {
	if ( typeof value == 'string' ) {
		return ( value == 'true' || value == 'True' || value == 'TRUE' || value == '1' );
	}
	if ( typeof value == 'boolean' ) {
		return value;
	}
	return ! ! parseInt( value );
};

$us.getScript = function( url, callback ) {
	if ( ! $us.ajaxLoadJs ) {
		callback();
		return false;
	}

	if ( $us.loadedScripts === undefined ) {
		$us.loadedScripts = {};
		$us.loadedScriptsFunct = {};
	}

	if ( $us.loadedScripts[ url ] === 'loaded' ) {
		callback();
		return;
	} else if ( $us.loadedScripts[ url ] === 'loading' ) {
		$us.loadedScriptsFunct[ url ].push( callback );
		return;
	}

	$us.loadedScripts[ url ] = 'loading';
	$us.loadedScriptsFunct[ url ] = [];
	$us.loadedScriptsFunct[ url ].push( callback )

	var complete = function() {
		for ( var i = 0; i < $us.loadedScriptsFunct[ url ].length; i ++ ) {
			$us.loadedScriptsFunct[ url ][ i ]();
		}
		$us.loadedScripts[ url ] = 'loaded';
	};

	var options = {
		dataType: "script",
		cache: true,
		url: url,
		complete: complete
	};

	return jQuery.ajax( options );
};

// Detecting IE browser
$us.detectIE = function() {
	var ua = window.navigator.userAgent;

	var msie = ua.indexOf( 'MSIE ' );
	if ( msie > 0 ) {
		// IE 10 or older => return version number
		return parseInt( ua.substring( msie + 5, ua.indexOf( '.', msie ) ), 10 );
	}

	var trident = ua.indexOf( 'Trident/' );
	if ( trident > 0 ) {
		// IE 11 => return version number
		var rv = ua.indexOf( 'rv:' );
		return parseInt( ua.substring( rv + 3, ua.indexOf( '.', rv ) ), 10 );
	}

	var edge = ua.indexOf( 'Edge/' );
	if ( edge > 0 ) {
		// Edge (IE 12+) => return version number
		return parseInt( ua.substring( edge + 5, ua.indexOf( '.', edge ) ), 10 );
	}

	// other browser
	return false;
};

// Fixing hovers for devices with both mouse and touch screen
jQuery.isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent );
jQuery( 'html' ).toggleClass( 'no-touch', ! jQuery.isMobile );
jQuery( 'html' ).toggleClass( 'ie11', $us.detectIE() == 11 );

/**
 * Commonly used jQuery objects
 */
! function( $ ) {
	$us.$window = $( window );
	$us.$document = $( document );
	$us.$html = $( 'html' );
	$us.$body = $( '.l-body:first' );
	$us.$htmlBody = $us.$html.add( $us.$body );
	$us.$canvas = $( '.l-canvas:first' );
}( jQuery );

// Extending Lazy Load
if ( jQuery.lazyLoadXT !== undefined && jQuery.lazyLoadXT.updateEvent !== undefined ) {
	jQuery.lazyLoadXT.updateEvent = jQuery.lazyLoadXT.updateEvent + ' click uslazyloadevent';
}

/**
 * $us.canvas
 *
 * All the needed data and functions to work with overall canvas.
 */
! function( $ ) {
	"use strict";

	function USCanvas( options ) {

		// Setting options
		var defaults = {
			disableEffectsWidth: 900,
			responsive: true,
			backToTopDisplay: 100
		};
		this.options = $.extend( {}, defaults, options || {} );

		// Commonly used dom elements
		this.$header = $us.$canvas.find( '.l-header' );
		this.$main = $us.$canvas.find( '.l-main' );
		this.$sections = $us.$canvas.find( '.l-section' );
		this.$firstSection = this.$sections.first();
		this.$secondSection = this.$sections.eq( 1 );
		this.$fullscreenSections = this.$sections.filter( '.height_full' );
		this.$topLink = $( '.w-toplink' );

		// Canvas modificators
		this.type = $us.$canvas.usMod( 'type' );
		// Initial header position
		this._headerPos = this.$header.usMod( 'pos' );
		// Current header position
		this.headerPos = this._headerPos;
		this.headerInitialPos = $us.$body.usMod( 'headerinpos' );
		this.headerBg = this.$header.usMod( 'bg' );
		this.rtl = $us.$body.hasClass( 'rtl' );

		// Will be used to count fullscreen sections heights and proper scroll positions
		this.scrolledOccupiedHeight = 0;

		// Used to prevent resize events on scroll for Android browsers
		this.isScrolling = false;
		this.scrollTimeout = false;
		this.isAndroid = /Android/i.test( navigator.userAgent );

		// If in iframe...
		if ( $us.$body.hasClass( 'us_iframe' ) ) {
			// change links so they lead to main window
			$( 'a:not([target])' ).each( function() {
				$( this ).attr( 'target', '_parent' )
			} );
			// hide preloader
			jQuery( function( $ ) {
				var $framePreloader = $( '.l-popup-box-content .g-preloader', window.parent.document );
				$framePreloader.hide();
			} );
		}

		// Boundable events
		this._events = {
			scroll: this.scroll.bind( this ),
			resize: this.resize.bind( this )
		};

		$us.$window.on( 'scroll', this._events.scroll );
		$us.$window.on( 'resize load', this._events.resize );
		// Complex logics requires two initial renders: before inner elements render and after
		setTimeout( this._events.resize, 25 );
		setTimeout( this._events.resize, 75 );
	}

	USCanvas.prototype = {

		/**
		 * Scroll-driven logics
		 */
		scroll: function() {
			var scrollTop = parseInt( $us.$window.scrollTop() );

			// Show/hide go to top link
			this.$topLink.toggleClass( 'active', ( scrollTop >= this.winHeight * this.options.backToTopDisplay / 100 ) );

			if ( this.isAndroid ) {
				this.isScrolling = true;
				if ( this.scrollTimeout ) {
					clearTimeout( this.scrollTimeout );
				}
				this.scrollTimeout = setTimeout( function() {
					this.isScrolling = false;
				}.bind( this ), 100 );
			}
		},

		/**
		 * Resize-driven logics
		 */
		resize: function() {
			// Window dimensions
			this.winHeight = parseInt( $us.$window.height() );
			this.winWidth = parseInt( $us.$window.width() );

			// Disabling animation on mobile devices
			$us.$body.toggleClass( 'disable_effects', ( this.winWidth < this.options.disableEffectsWidth ) );

			// Vertical centering of fullscreen sections in IE 11
			var ieVersion = $us.detectIE();
			if ( ( ieVersion !== false && ieVersion == 11 ) && ( this.$fullscreenSections.length > 0 && ! this.isScrolling ) ) {
				var adminBar = $( '#wpadminbar' ),
					adminBarHeight = ( adminBar.length ) ? adminBar.height() : 0;
				this.$fullscreenSections.each( function( index, section ) {
					var $section = $( section ),
						sectionHeight = this.winHeight,
						isFirstSection = ( index == 0 && $section.is( this.$firstSection ) );
					// First section
					if ( isFirstSection ) {
						sectionHeight -= $section.offset().top;
					}
					// 2+ sections
					else {
						sectionHeight -= $us.header.scrolledOccupiedHeight + adminBarHeight;
					}
					if ( $section.hasClass( 'valign_center' ) ) {
						var $sectionH = $section.find( '.l-section-h' ),
							sectionTopPadding = parseInt( $section.css( 'padding-top' ) ),
							contentHeight = $sectionH.outerHeight(),
							topMargin;
						$sectionH.css( 'margin-top', '' );
						// Section was extended by extra top padding that is overlapped by fixed solid header and not
						// visible
						var sectionOverlapped = isFirstSection && $us.header.pos == 'fixed' && $us.header.bg != 'transparent' && $us.header.orientation != 'ver';
						if ( sectionOverlapped ) {
							// Part of first section is overlapped by header
							topMargin = Math.max( 0, ( sectionHeight - sectionTopPadding - contentHeight ) / 2 );
						} else {
							topMargin = Math.max( 0, ( sectionHeight - contentHeight ) / 2 - sectionTopPadding );
						}
						$sectionH.css( 'margin-top', topMargin || '' );
					}
				}.bind( this ) );
				$us.$canvas.trigger( 'contentChange' );
			}

			// If the page is loaded in iframe
			if ( $us.$body.hasClass( 'us_iframe' ) ) {
				var $frameContent = $( '.l-popup-box-content', window.parent.document ),
					outerHeight = $us.$body.outerHeight( true );
				if ( outerHeight > 0 && $( window.parent ).height() > outerHeight ) {
					$frameContent.css( 'height', outerHeight );
				} else {
					$frameContent.css( 'height', '' );
				}
			}

			// Fix scroll glitches that could occur after the resize
			this.scroll();
		}
	};

	$us.canvas = new USCanvas( $us.canvasOptions || {} );

}( jQuery );

/**
 * CSS-analog of jQuery slideDown/slideUp/fadeIn/fadeOut functions (for better rendering)
 */
! function() {

	/**
	 * Remove the passed inline CSS attributes.
	 *
	 * Usage: $elm.resetInlineCSS('height', 'width');
	 */
	jQuery.fn.resetInlineCSS = function() {
		for ( var index = 0; index < arguments.length; index ++ ) {
			this.css( arguments[ index ], '' );
		}
		return this;
	};

	jQuery.fn.clearPreviousTransitions = function() {
		// Stopping previous events, if there were any
		var prevTimers = ( this.data( 'animation-timers' ) || '' ).split( ',' );
		if ( prevTimers.length >= 2 ) {
			this.resetInlineCSS( 'transition' );
			prevTimers.map( clearTimeout );
			this.removeData( 'animation-timers' );
		}
		return this;
	};
	/**
	 *
	 * @param {Object} css key-value pairs of animated css
	 * @param {Number} duration in milliseconds
	 * @param {Function} onFinish
	 * @param {String} easing CSS easing name
	 * @param {Number} delay in milliseconds
	 */
	jQuery.fn.performCSSTransition = function( css, duration, onFinish, easing, delay ) {
		duration = duration || 250;
		delay = delay || 25;
		easing = easing || 'ease';
		var $this = this,
			transition = [];

		this.clearPreviousTransitions();

		for ( var attr in css ) {
			if ( ! css.hasOwnProperty( attr ) ) {
				continue;
			}
			transition.push( attr + ' ' + ( duration / 1000 ) + 's ' + easing );
		}
		transition = transition.join( ', ' );
		$this.css( {
			transition: transition
		} );

		// Starting the transition with a slight delay for the proper application of CSS transition properties
		var timer1 = setTimeout( function() {
			$this.css( css );
		}, delay );

		var timer2 = setTimeout( function() {
			$this.resetInlineCSS( 'transition' );
			if ( typeof onFinish == 'function' ) {
				onFinish();
			}
		}, duration + delay );

		this.data( 'animation-timers', timer1 + ',' + timer2 );
	};

	// Height animations
	jQuery.fn.slideDownCSS = function( duration, onFinish, easing, delay ) {
		if ( this.length == 0 ) {
			return;
		}
		var $this = this;
		this.clearPreviousTransitions();
		// Grabbing paddings
		this.resetInlineCSS( 'padding-top', 'padding-bottom' );
		var timer1 = setTimeout( function() {
			var paddingTop = parseInt( $this.css( 'padding-top' ) ),
				paddingBottom = parseInt( $this.css( 'padding-bottom' ) );
			// Grabbing the "auto" height in px
			$this.css( {
				visibility: 'hidden',
				position: 'absolute',
				height: 'auto',
				'padding-top': 0,
				'padding-bottom': 0,
				display: 'block'
			} );
			var height = $this.height();
			$this.css( {
				overflow: 'hidden',
				height: '0px',
				opacity: 0,
				visibility: '',
				position: ''
			} );
			$this.performCSSTransition( {
				opacity: 1,
				height: height + paddingTop + paddingBottom,
				'padding-top': paddingTop,
				'padding-bottom': paddingBottom
			}, duration, function() {
				$this.resetInlineCSS( 'overflow' ).css( 'height', 'auto' );
				if ( typeof onFinish == 'function' ) {
					onFinish();
				}
			}, easing, delay );
		}, 25 );
		this.data( 'animation-timers', timer1 + ',null' );
	};
	jQuery.fn.slideUpCSS = function( duration, onFinish, easing, delay ) {
		if ( this.length == 0 ) {
			return;
		}
		this.clearPreviousTransitions();
		this.css( {
			height: this.outerHeight(),
			overflow: 'hidden',
			'padding-top': this.css( 'padding-top' ),
			'padding-bottom': this.css( 'padding-bottom' )
		} );
		var $this = this;
		this.performCSSTransition( {
			height: 0,
			opacity: 0,
			'padding-top': 0,
			'padding-bottom': 0
		}, duration, function() {
			$this.resetInlineCSS( 'overflow', 'padding-top', 'padding-bottom' ).css( {
				display: 'none'
			} );
			if ( typeof onFinish == 'function' ) {
				onFinish();
			}
		}, easing, delay );
	};

	// Opacity animations
	jQuery.fn.fadeInCSS = function( duration, onFinish, easing, delay ) {
		if ( this.length == 0 ) {
			return;
		}
		this.clearPreviousTransitions();
		this.css( {
			opacity: 0,
			display: 'block'
		} );
		this.performCSSTransition( {
			opacity: 1
		}, duration, onFinish, easing, delay );
	};
	jQuery.fn.fadeOutCSS = function( duration, onFinish, easing, delay ) {
		if ( this.length == 0 ) {
			return;
		}
		var $this = this;
		this.performCSSTransition( {
			opacity: 0
		}, duration, function() {
			$this.css( 'display', 'none' );
			if ( typeof onFinish == 'function' ) {
				onFinish();
			}
		}, easing, delay );
	};
}();

jQuery( function( $ ) {
	"use strict";

	// Force popup opening on links with ref
	if ( $( 'a[ref=magnificPopup][class!=direct-link]' ).length != 0 ) {
		$us.getScript( $us.templateDirectoryUri + '/framework/js/vendor/magnific-popup.js', function() {
			$( 'a[ref=magnificPopup][class!=direct-link]' ).magnificPopup( {
				type: 'image',
				removalDelay: 300,
				mainClass: 'mfp-fade',
				fixedContentPos: true
			} );
		} );
	}

	// Hide background images until are loaded
	jQuery( '.l-section-img' ).each( function() {
		var $this = $( this ),
			img = new Image(),
			bgImg = $this.css( 'background-image' ) || '';

		// If the background image CSS seems to be valid, preload an image and then show it
		if ( bgImg.match( /url\(['"]*(.*?)['"]*\)/i ) ) {
			img.onload = function() {
				if ( ! $this.hasClass( 'loaded' ) ) {
					$this.addClass( 'loaded' );
				}
			};
			img.src = bgImg.replace( /url\(['"]*(.*?)['"]*\)/i, '$1' );
			// If we cannot parse the background image CSS, just add loaded class to the background tag so a background
			// image is shown anyways
		} else {
			$this.addClass( 'loaded' );
		}
	} );

	/* YouTube/Vimeo background */
	$( window ).on( 'resize load', function() {
		var $container = $( '.with_youtube, .with_vimeo' );

		if ( ! $container.length ) {
			return;
		}

		$container.each( function() {
			this.$container = $( this );

			var $frame = this.$container.find( 'iframe' ),
				cHeight = this.$container.innerHeight(),
				cWidth = this.$container.innerWidth(),
				fWidth = '',
				fHeight = '';

			if ( cWidth / cHeight < 16 / 9 ) {
				fWidth = cHeight * ( 16 / 9 );
				fHeight = cHeight;
			} else {
				fWidth = cWidth;
				fHeight = fWidth * ( 9 / 16 );
			}

			$frame.css( {
				'width': Math.round( fWidth ),
				'height': Math.round( fHeight ),
			} );
		} );
	} );

} );