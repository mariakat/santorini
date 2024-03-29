/**
 * UpSolution Element: w-cart
 *
 * @requires $us.canvas
 * @requires $us.nav
 */
jQuery( function( $ ) {
	var $cart = $( '.w-cart' );
	if ( $cart.length == 0 ) {
		return;
	}
	var $quantity = $cart.find( '.w-cart-quantity' );

	var us_accessibility = function() {
		$cart.find( 'a' ).on( 'focus.upsolution', function() {
			$( this ).closest( '.w-cart' ).addClass( 'opened' );
		} );
		$cart.find( 'a' ).on( 'blur.upsolution', function() {
			$( this ).closest( '.w-cart' ).removeClass( 'opened' );
		} );
	};

	us_accessibility();

	var updateCart = function() {
		if ( $cart.hasClass( 'opened' ) ) {
			$cart.removeClass( 'opened' );
		}
		var $mini_cart_amount = $cart.find( '.us_mini_cart_amount' ).first(),
			mini_cart_amount = $mini_cart_amount.text();

		if ( mini_cart_amount !== undefined ) {
			mini_cart_amount = mini_cart_amount + '';
			mini_cart_amount = mini_cart_amount.match( /\d+/g );

			if ( mini_cart_amount > 0 ) {
				$quantity.html( mini_cart_amount );
				$cart.removeClass( 'empty' );
			} else {
				$quantity.html( '0' );
				$cart.addClass( 'empty' );
			}
		} else {
			// fallback in case our action wasn't fired somehow
			var $quantities = $cart.find( '.quantity' ),
				total = 0;
			$quantities.each( function() {
				var quantity,
					text = $( this ).text() + '',
					matches = text.match( /\d+/g );

				if ( matches ) {
					quantity = parseInt( matches[ 0 ], 10 );
					total += quantity;
				}
			} );
			if ( total > 0 ) {
				$quantity.html( total );
				$cart.removeClass( 'empty' );
			} else {
				$quantity.html( '0' );
				$cart.addClass( 'empty' );
			}
		}
	};

	updateCart();

	$( document.body ).bind( 'wc_fragments_loaded', function() {
		updateCart();
		us_accessibility();
	} );

	$( document.body ).bind( 'wc_fragments_refreshed', function() {
		updateCart();
		us_accessibility();
	} );

	var $notification = $cart.find( '.w-cart-notification' ),
		$productName = $notification.find( '.product-name' ),
		$cartLink = $cart.find( '.w-cart-link' ),
		$dropdown = $cart.find( '.w-cart-dropdown' ),
		$quantity = $cart.find( '.w-cart-quantity' ),
		productName = $productName.text(),
		showFn = 'fadeInCSS',
		hideFn = 'fadeOutCSS',
		opened = false;

	$notification.on( 'click', function() {
		$notification[ hideFn ]();
	} );

	jQuery( 'body' ).bind( 'added_to_cart', function( event, fragments, cart_hash, $button ) {
		if ( event === undefined ) {
			return;
		}

		updateCart();

		productName = $button.closest( '.product' ).find( '.woocommerce-loop-product__title' ).text();
		$productName.html( productName );

		$notification.addClass( 'shown' );
		$notification.on( 'mouseenter', function() {
			$notification.removeClass( 'shown' );
		} );

		var newTimerId = setTimeout( function() {
			$notification.removeClass( 'shown' );
			$notification.off( 'mouseenter' );
		}, 3000 );

	} );

	if ( $.isMobile ) {
		var outsideClickEvent = function( e ) {
			if ( jQuery.contains( $cart[ 0 ], e.target ) ) {
				return;
			}
			$cart.removeClass( 'opened' );
			$us.$body.off( 'touchstart', outsideClickEvent );
			opened = false;
		};
		$cartLink.on( 'click', function( e ) {
			if ( ! opened ) {
				e.preventDefault();
				$cart.addClass( 'opened' );
				$us.$body.on( 'touchstart', outsideClickEvent );
			} else {
				$cart.removeClass( 'opened' );
				$us.$body.off( 'touchstart', outsideClickEvent );
			}
			opened = ! opened;
		} );
	}
} );