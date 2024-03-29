/**
 * UpSolution Element: Page Scroller
 */
( function( $ ) {
	"use strict";

	$us.PageScroller = function( container, options ) {
		this.init( container, options );
	};

	$us.PageScroller.prototype = {
		init: function( container, options ) {
			var defaults = {
					coolDown: 100,
					animationDuration: 1000,
					animationEasing: 'easeInOutExpo'
				},
				scrollTop = parseInt( $us.$window.scrollTop() );

			this.options = $.extend( {}, defaults, options );

			this.$container = $( container );
			this._canvasTopOffset = $us.$canvas.offset().top;
			this.activeSection = 0;
			this.sections = [];
			this.dots = [];
			this.scrolls = [];
			this.usingDots = false;
			this.isTouch = ( ( 'ontouchstart' in window ) || ( navigator.msMaxTouchPoints > 0 ) || ( navigator.maxTouchPoints ) );
			this.disableWidth = ( this.$container.data( 'disablewidth' ) !== undefined ) ? this.$container.data( 'disablewidth' ) : 768;

			if ( this.$container.data( 'speed' ) !== undefined ) {
				this.options.animationDuration = this.$container.data( 'speed' );
			}

			// Adding header if isn't sticky
			if ( $us.canvas.headerPos == 'static' && $us.header.orientation == 'hor' ) {
				$us.canvas.$header.each( function() {
					var $section = $us.canvas.$header,
						section = {
							$section: $section
						};
					this._countPosition( section );
					this.sections.push( section );
				}.bind( this ) );
			}

			// Adding canvas sections
			$us.$canvas.find( '.l-section' ).each( function( key, elm ) {
				var $section = $( elm ),
					section = {
						$section: $section
					};
				this._countPosition( section );
				this.sections.push( section );
			}.bind( this ) );

			// Adding dots for canvas sections
			this.$dotsContainer = this.$container.find( '.w-scroller-dots' );
			if ( this.$dotsContainer.length ) {
				this.usingDots = true;

				this.$firstDot = this.$dotsContainer.find( '.w-scroller-dot' ).first();
				for ( var i = 1; i < this.sections.length; i ++ ) {
					this.$firstDot.clone().appendTo( this.$dotsContainer );
				}

				this.$dots = this.$dotsContainer.find( '.w-scroller-dot' );
				this.$dots.each( function( key, elm ) {
					var $dot = $( elm );
					this.dots[ key ] = $dot;
					$dot.click( function() {
						this.scrollTo( key );
						this.$dots.removeClass( 'active' );
						$dot.addClass( 'active' );
					}.bind( this ) );
				}.bind( this ) );

				this.dots[ this.activeSection ].addClass( 'active' );

				this.$dotsContainer.addClass( 'show' );
			}

			// Adding footer sections
			$( '.l-footer > .l-section' ).each( function( key, elm ) {
				var $section = $( elm ),
					section = {
						$section: $section
					};
				this._countPosition( section );
				this.sections.push( section );
			}.bind( this ) );

			this._attachEvents();

			// Boundable events
			this._events = {
				scroll: this.scroll.bind( this ),
				resize: this.resize.bind( this )
			};

			$us.$canvas.on( 'contentChange', this._events.resize );
			$us.$window.on( 'resize load', this._events.resize );
			$us.$window.on( 'resize load scroll', this._events.scroll );
			setTimeout( this._events.resize, 100 );
		},
		getScrollSpeed: function( number ) {
			var sum = 0;
			var lastElements = this.scrolls.slice( Math.max( this.scrolls.length - number, 1 ) );

			for ( var i = 0; i < lastElements.length; i ++ ) {
				sum = sum + lastElements[ i ];
			}

			return Math.ceil( sum / number );
		},
		_attachEvents: function() {

			$us.$document.off( 'mousewheel DOMMouseScroll MozMousePixelScroll' );
			$us.$canvas.off( 'touchstart touchmove' );

			if ( $us.$window.width() > this.disableWidth && $us.mobileNavOpened <= 0 && ( ! $us.$html.hasClass( 'cloverlay_fixed' ) ) ) {
				$us.$document.on( 'mousewheel DOMMouseScroll MozMousePixelScroll', function( e ) {
					e.preventDefault();
					var currentTime = new Date().getTime(),
						target = this.activeSection,
						direction = e.originalEvent.wheelDelta || - e.originalEvent.detail,
						speedEnd, speedMiddle, isAccelerating;


					if ( this.scrolls.length > 149 ) {
						this.scrolls.shift();
					}
					this.scrolls.push( Math.abs( direction ) );

					if ( ( currentTime - this.previousMouseWheelTime ) > this.options.coolDown ) {
						this.scrolls = [];
					}
					this.previousMouseWheelTime = currentTime;

					speedEnd = this.getScrollSpeed( 10 );
					speedMiddle = this.getScrollSpeed( 70 );
					isAccelerating = speedEnd >= speedMiddle;

					if ( isAccelerating ) {
						if ( direction < 0 ) {
							target ++;
						} else if ( direction > 0 ) {
							target --;
						}
						if ( this.sections[ target ] == undefined ) {
							return;
						}
						this.scrollTo( target );
						this.lastScroll = currentTime;
					}

				}.bind( this ) );

				if ( $.isMobile || this.isTouch ) {
					$us.$canvas.on( 'touchstart', function( event ) {
						var e = event.originalEvent;
						if ( typeof e.pointerType === 'undefined' || e.pointerType != 'mouse' ) {
							this.touchStartY = e.touches[ 0 ].pageY;
						}
					}.bind( this ) );

					$us.$canvas.on( 'touchmove', function( event ) {
						event.preventDefault();

						var currentTime = new Date().getTime(),
							e = event.originalEvent,
							target = this.activeSection;
						this.touchEndY = e.touches[ 0 ].pageY;

						if ( Math.abs( this.touchStartY - this.touchEndY ) > ( $us.$window.height() / 50 ) ) {
							if ( this.touchStartY > this.touchEndY ) {
								target ++;
							} else if ( this.touchEndY > this.touchStartY ) {
								target --;
							}

							if ( this.sections[ target ] == undefined ) {
								return;
							}
							this.scrollTo( target );
							this.lastScroll = currentTime;
						}
					}.bind( this ) );
				}
			}

		},
		_countPosition: function( section ) {
			section.top = section.$section.offset().top - this._canvasTopOffset;
			if ( $us.header.headerTop === undefined || ( $us.header.headerTop > 0 && section.top > $us.header.headerTop ) ) {
				section.top = section.top - $us.header.scrolledOccupiedHeight;
			}
			section.bottom = section.top + section.$section.outerHeight( false );
		},
		_countAllPositions: function() {
			for ( var section in this.sections ) {
				if ( this.sections[ section ].$section.length ) {
					this._countPosition( this.sections[ section ] );
				}
			}
		},
		scrollTo: function( target ) {
			var currentTime = new Date().getTime();
			if ( this.previousScrollTime !== undefined && ( currentTime - this.previousScrollTime < this.options.animationDuration ) ) {
				return;
			}
			this.previousScrollTime = currentTime;

			if ( this.usingDots ) {
				this.$dots.removeClass( 'active' );
				if ( this.dots[ target ] !== undefined ) {
					this.dots[ target ].addClass( 'active' );
				}
			}

			$us.$htmlBody.stop( true, false ).animate( {
				scrollTop: this.sections[ target ][ 'top' ] + 'px'
			}, {
				duration: this.options.animationDuration,
				easing: this.options.animationEasing,
				always: function() {
					this.activeSection = target;
				}.bind( this )
			} );
		},
		resize: function() {
			this._attachEvents();

			// Delaying the resize event to prevent glitches
			setTimeout( function() {
				this._countAllPositions();
				// this.scrollTo(this.activeSection);
			}.bind( this ), 150 );
			this._countAllPositions();
			// this.scrollTo(this.activeSection);
		},
		scroll: function() {
			var currentTime = new Date().getTime();
			if ( ( currentTime - this.lastScroll ) < ( this.options.coolDown + this.options.animationDuration ) ) {
				return;
			}
			if ( this.scrollTimeout ) {
				clearTimeout( this.scrollTimeout );
			}
			this.scrollTimeout = setTimeout( function() {
				var scrollTop = parseInt( $us.$window.scrollTop() );

				for ( var section in this.sections ) {
					if ( scrollTop >= ( this.sections[ section ].top - 1 ) && scrollTop < ( this.sections[ section ].bottom - 1 ) ) {
						this.activeSection = section;
						break;
					}
				}
				if ( this.usingDots ) {
					this.$dots.removeClass( 'active' );
					if ( this.dots[ this.activeSection ] !== undefined ) {
						this.dots[ this.activeSection ].addClass( 'active' );
					}
				}
			}.bind( this ), 500 );
		}
	};

	$.fn.usPageScroller = function( options ) {
		return this.each( function() {
			$( this ).data( 'usPageScroller', new $us.PageScroller( this, options ) );
		} );
	};

	$( function() {
		$( '.w-scroller' ).usPageScroller();
	} );
} )( jQuery );