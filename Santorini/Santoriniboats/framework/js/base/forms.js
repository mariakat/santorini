/**
 * UpSolution Shortcode: us_cform
 */
jQuery( function( $ ) {

	$( '.w-form.for_cform' ).each( function() {
		var $container = $( this ),
			$form = $container.find( 'form:first' ),
			$submitBtn = $form.find( '.w-btn' ),
			$resultField = $form.find( '.w-form-message' ),
			options = $container.find( '.w-form-json' )[ 0 ].onclick(),
			$requiredCheckboxes = $form.find( '.for_checkboxes.required' );
		$container.find( '.w-form-json' ).remove();

		$form.submit( function( event ) {
			event.preventDefault();

			// Prevent double-sending
			if ( $submitBtn.hasClass( 'loading' ) ) {
				return;
			}

			$resultField.usMod( 'type', false ).html( '' );
			// Validation
			var errors = 0;
			$form.find( '[data-required="true"]' ).each( function() {
				var $input = $( this ),
					isEmpty = $input.is( '[type="checkbox"]' ) ? ( ! $input.is( ':checked' ) ) : ( $input.val() == '' ),
					$row = $input.closest( '.w-form-row' );
				// Skip checkboxes
				if ( $row.hasClass( 'for_checkboxes' ) ) {
					return true;
				}
				$row.toggleClass( 'check_wrong', isEmpty );
				if ( isEmpty ) {
					errors ++;
				}
			} );

			// Count required checkboxes separately
			if ( $requiredCheckboxes.length ) {
				$requiredCheckboxes.each( function() {
					var $input = $( this ).find( 'input[type="checkbox"]' ),
						$row = $input.closest( '.w-form-row' ),
						isEmpty = ! $input.is( ':checked' ) ? true : false;
					$row.toggleClass( 'check_wrong', isEmpty );
					if ( isEmpty ) {
						errors ++;
					}
				} );
			}

			if ( errors != 0 ) {
				return;
			}

			$submitBtn.addClass( 'loading' );
			$.ajax( {
				type: 'POST',
				url: options.ajaxurl,
				dataType: 'json',
				data: $form.serialize(),
				success: function( result ) {
					if ( result.success ) {
						$resultField.usMod( 'type', 'success' ).html( result.data );
						$form.find( '.w-form-row.check_wrong' ).removeClass( 'check_wrong' );
						$form.find( '.w-form-row.not-empty' ).removeClass( 'not-empty' );
						$form.find( 'input[type="text"], input[type="email"], textarea' ).val( '' );
						$form[ 0 ].reset();
					} else {
						$form.find( '.w-form-row.check_wrong' ).removeClass( 'check_wrong' );
						if ( result.data && typeof result.data == 'object' ) {
							for ( var fieldName in result.data ) {
								if ( fieldName == 'empty_message' ) {
									$resultField.usMod( 'type', 'error' );
									continue;
								}

								if ( ! result.data.hasOwnProperty( fieldName ) ) {
									continue;
								}

								fieldName = result.data[ fieldName ].name;
								var $input = $form.find( '[name="' + fieldName + '"]' );
								$input.closest( '.w-form-row' ).addClass( 'check_wrong' )
							}
						} else {
							$resultField.usMod( 'type', 'error' ).html( result.data );
						}
					}
				},
				complete: function() {
					$submitBtn.removeClass( 'loading' );
				}
			} );
		} );

	} );
} );

/**
 * UpSolution Login Widget: widget_us_login
 *
 */
! function( $ ) {
	"use strict";

	$us.WLogin = function( container, options ) {
		this.init( container, options );
	};

	$us.WLogin.prototype = {
		init: function( container, options ) {
			this.$container = $( container );

			// Prevent double init
			if ( this.$container.data( 'loginInit' ) == 1 ) {
				return;
			}
			this.$container.data( 'loginInit', 1 );

			this.$form = this.$container.find( '.w-form' );
			this.$profile = this.$container.find( '.w-profile' );
			this.$preloader = this.$container.find( 'div.g-preloader' );
			this.$submitBtn = this.$form.find( '.w-btn' );
			this.$username = this.$form.find( '.for_text input[type="text"]' );
			this.$password = this.$form.find( '.for_password input[type="password"]' );
			this.$nonceVal = this.$form.find( '#us_login_nonce' ).val();
			this.$resultField = this.$form.find( '.w-form-message' );

			this.$jsonContainer = this.$container.find( '.w-profile-json' );
			this.jsonData = this.$jsonContainer[ 0 ].onclick() || {};
			this.$jsonContainer.remove();

			this.ajaxUrl = this.jsonData.ajax_url || '';
			this.logoutRedirect = this.jsonData.logout_redirect || '/';
			this.loginRedirect = this.jsonData.login_redirect || '';

			this._events = {
				formSubmit: this.formSubmit.bind( this )
			};

			this.$form.on( 'submit', this._events.formSubmit );

			$.ajax( {
				type: 'post',
				url: this.ajaxUrl,
				data: {
					action: 'us_ajax_user_info',
					logout_redirect: this.logoutRedirect
				},
				success: function( result ) {
					if ( result.success ) {
						var $avatar = this.$profile.find( '.w-profile-avatar' ),
							$name = this.$profile.find( '.w-profile-name' ),
							$logoutLink = this.$profile.find( '.w-profile-link.for_logout' );

						$avatar.html( result.data.avatar );
						$name.html( result.data.name );
						$logoutLink.attr( 'href', result.data.logout_url );
						this.$profile.removeClass( 'hidden' );
					} else {
						this.$form.removeClass( 'hidden' );
					}
					this.$preloader.addClass( 'hidden' );
				}.bind( this )
			} );
		},
		formSubmit: function( event ) {
			event.preventDefault();

			// Prevent double-sending
			if ( this.$submitBtn.hasClass( 'loading' ) ) {
				return;
			}

			// Clear errors
			this.$resultField.usMod( 'type', false ).html( '' );
			this.$form.find( '.w-form-row.check_wrong' ).removeClass( 'check_wrong' );
			this.$form.find( '.w-form-state' ).html( '' );

			// Prevent sending data with empty username
			if ( this.$form.find( '.for_text input[type="text"]' ).val() == '' ) {
				this.$username.closest( '.w-form-row' ).toggleClass( 'check_wrong' );
				return;
			}

			this.$submitBtn.addClass( 'loading' );
			$.ajax( {
				type: 'post',
				url: this.ajaxUrl,
				dataType: 'json',
				data: {
					action: 'us_ajax_login',
					username: this.$username.val(),
					password: this.$password.val(),
					us_login_nonce: this.$nonceVal
				},
				success: function( result ) {
					if ( result.success ) {
						document.location.href = this.loginRedirect;
					} else {
						if ( result.data.code == 'invalid_username' ) {
							var $rowLog = this.$username.closest( '.w-form-row' );
							$rowLog.toggleClass( 'check_wrong' );
							$rowLog.find( '.w-form-row-state' ).html( result.data.message ? result.data.message : '' );
						} else if ( result.data.code == 'incorrect_password' || result.data.code == 'empty_password' ) {
							var $rowPwd = this.$password.closest( '.w-form-row' );
							$rowPwd.toggleClass( 'check_wrong' );
							$rowPwd.find( '.w-form-row-state' ).html( result.data.message ? result.data.message : '' );
						} else {
							this.$resultField.usMod( 'type', 'error' ).html( result.data.message );
						}
					}
					this.$submitBtn.removeClass( 'loading' );
				}.bind( this ),
			} );
		}
	};

	$.fn.wUsLogin = function( options ) {
		return this.each( function() {
			$( this ).data( 'wUsLogin', new $us.WLogin( this, options ) );
		} );
	};

	$( function() {
		$( '.widget_us_login' ).wUsLogin();
	} );
}( jQuery );

/**
 * Form customs
 */
jQuery( function( $ ) {

	// Add not-empty class when filling form fields
	$( 'input[type="text"], input[type="email"], input[type="tel"], input[type="number"], input[type="date"], input[type="search"], input[type="url"], input[type="password"], textarea' ).each( function( index, input ) {
		var $input = $( input ),
			$row = $input.closest( '.w-form-row' );
		if ( $input.attr( 'type' ) == 'hidden' ) {
			return;
		}
		$row.toggleClass( 'not-empty', $input.val() != '' );
		$input.on( 'input', function() {
			$row.toggleClass( 'not-empty', $input.val() != '' );
		} );
	} );

	// Add focused class for all form fields
	$( document ).on( 'focus', '.w-form-row-field input, .w-form-row-field textarea', function() {
		$( this ).closest( '.w-form-row' ).addClass( 'focused' );
	} );
	$( document ).on( 'blur', '.w-form-row-field input, .w-form-row-field textarea', function() {
		$( this ).closest( '.w-form-row' ).removeClass( 'focused' );
	} );
} );