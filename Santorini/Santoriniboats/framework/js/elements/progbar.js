/**
 * UpSolution Element: Progbar
 */
( function( $ ) {
	$.fn.wProgbar = function() {
		return this.each( function() {
			var $container = $( this ),
				count = $container.data( 'count' ) + '',
				$titleCount = $container.find( '.w-progbar-title-count' ),
				$barCount = $container.find( '.w-progbar-bar-count' );

			// Prevent double init
			if ( $container.data( 'progBarInit' ) == 1 ) {
				return;
			}
			$container.data( 'progBarInit', 1 );

			if ( count === null ) {
				count = 50;
			}

			if ( /bot|googlebot|crawler|spider|robot|crawling/i.test( navigator.userAgent ) ) {
				$container.removeClass( 'initial' );
				$titleCount.html( count + '%' );
				$barCount.html( count + '%' );
				return;
			}

			$titleCount.html( '0%' );
			$barCount.html( '0%' );

			$us.scroll.addWaypoint( this, '15%', function() {
				var current = 0,
					step = 40,
					stepValue = count / 40,
					interval = setInterval( function() {
						current += stepValue;
						step --;
						$titleCount.html( current.toFixed( 0 ) + '%' );
						$barCount.html( current.toFixed( 0 ) + '%' );
						if ( step <= 0 ) {
							$titleCount.html( count + '%' );
							$barCount.html( count + '%' );
							window.clearInterval( interval );
						}
					}, 20 );

				$container.removeClass( 'initial' );
			} );
		} );
	};

	$( function() {
		jQuery( '.w-progbar' ).wProgbar();
	} );
} )( jQuery );