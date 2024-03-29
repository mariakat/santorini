/**
 * UpSolution Element: Dropdown
 */
( function( $ ) {
	"use strict";
	$.fn.wDropdown = function() {
		return this.each( function() {
			var $this = $( this ),
				$list = $this.find( '.w-dropdown-list' ),
				$current = $this.find( '.w-dropdown-current' ),
				$currentAnchor = $current.find( 'a' ),
				$anchors = $this.find( 'a' ),
				openEventName = 'click',
				closeEventName = 'mouseup touchstart mousewheel DOMMouseScroll touchstart',
				justOpened = false;
			if ( $this.hasClass( 'open_on_hover' ) ) {
				openEventName = 'mouseenter';
				closeEventName = 'mouseleave';
			}
			var closeList = function() {
				$this.removeClass( 'opened' );
				$us.$window.off( closeEventName, closeListEvent );
			};
			var closeListEvent = function( e ) {
				if ( closeEventName != 'mouseleave' && $this.has( e.target ).length !== 0 ) {
					return;
				}
				e.stopPropagation();
				e.preventDefault();
				closeList();
			};
			var openList = function() {
				$this.addClass( 'opened' );
				if ( closeEventName == 'mouseleave' ) {
					$this.on( closeEventName, closeListEvent );
				} else {
					$us.$window.on( closeEventName, closeListEvent );
				}

				justOpened = true;
				window.setTimeout( function() {
					justOpened = false;
				}, 500 );
			};
			var openListEvent = function( e ) {
				if ( openEventName == 'click' && $this.hasClass( 'opened' ) && ! justOpened ) {
					closeList();
					return;
				}
				openList();
			};

			$current.on( openEventName, openListEvent );

			$anchors.on( 'focus.upsolution', function() {
				openList();
			} );
			$this.on( 'keydown', function( e ) {
				var keyCode = e.keyCode || e.which;
				if ( keyCode == 9 ) {
					var $target = $( e.target ) ? $( e.target ) : {},
						index = $anchors.index( $target );

					if ( e.shiftKey ) {
						if ( index === 0 ) {
							closeList();
						}
					} else {
						if ( index === $anchors.length - 1 ) {
							closeList();
						}
					}
				}
			} );
		} );
	};
	$( function() {
		$( '.w-dropdown' ).wDropdown();
	} );
} )( jQuery );