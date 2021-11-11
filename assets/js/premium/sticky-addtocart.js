// Function for Sticky Add to Cart functionality
( function() {
	document.addEventListener( 'DOMContentLoaded', function() {
		var wcz_sticky_addtocart = document.getElementsByClassName( 'wcz-sticky-addtocart' );

		if ( ! wcz_sticky_addtocart.length ) {
			return;
		}

		var trigger = document.getElementsByClassName( 'entry-summary' );

		if ( trigger.length > 0 ) {
			var wcz_toggle_sticky_addtocart = function() {
				if ( ( trigger[0].getBoundingClientRect().top + trigger[0].scrollHeight ) < 0 ) {
					wcz_sticky_addtocart[0].classList.add( 'wcz_sticky_addtocart-show' );
					wcz_sticky_addtocart[0].classList.remove( 'wcz_sticky_addtocart-hide' );
				} else if ( wcz_sticky_addtocart[0].classList.contains( 'wcz_sticky_addtocart-show' ) ) {
					wcz_sticky_addtocart[0].classList.add( 'wcz_sticky_addtocart-hide' );
					wcz_sticky_addtocart[0].classList.remove( 'wcz_sticky_addtocart-show' );
				}
			};

			wcz_toggle_sticky_addtocart();

			window.addEventListener( 'scroll', function() {
				wcz_toggle_sticky_addtocart();
			} );

			// Get product id
			var product_id = null;

			document.body.classList.forEach( function( item ){
				if ( 'postid-' === item.substring( 0, 7 ) ) {
					product_id = item.replace( /[^0-9]/g, '' );
				}
			} );

			if ( product_id ) {
				var product = document.getElementById( 'product-' + product_id );

				if ( product ) {
					if ( ! product.classList.contains( 'product-type-external' ) ) {
						var selectOptions = document.getElementsByClassName( 'wcz-sticky-addtocart-button' );

						selectOptions[0].addEventListener( 'click', function( event ) {
							event.preventDefault();
							var scrolltoproduct = document.getElementById( 'product-' + product_id ).offsetTop;

							window.scrollTo({
								top: scrolltoproduct,
								behavior: 'smooth'
							});
						} );
					}
				}
			}
		}
	} );
} )();
