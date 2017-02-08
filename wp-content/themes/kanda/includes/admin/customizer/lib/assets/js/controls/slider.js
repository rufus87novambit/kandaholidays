wp.customize.controlConstructor['kirki-slider'] = wp.customize.Control.extend({

	ready: function() {

		'use strict';

		var control = this,
		    value,
		    thisInput,
		    inputDefault,
		    changeAction;

		// Update the text value
		jQuery( 'input[type="range"]' ).on( 'mousedown', function() {
			var _label = jQuery( this ).closest( 'label' ).find( '.kirki_range_value .value'),
				_value = jQuery( this ).attr( 'value' );

			_label.text( _value );
			jQuery( this ).mousemove( function() {
				_value = jQuery( this).val();

				_label.text( _value );
			});
		});

		// Handle the reset button
		jQuery( '.kirki-slider-reset' ).click( function() {
			thisInput    = jQuery( this ).closest( 'label' ).find( 'input' );
			inputDefault = thisInput.data( 'reset_value' );
			thisInput.val( inputDefault );
			thisInput.change();
			jQuery( this ).closest( 'label' ).find( '.kirki_range_value .value' ).text( inputDefault );
		});

		if ( 'postMessage' === control.setting.transport ) {
			changeAction = 'mousemove change';
		} else {
			changeAction = 'change';
		}

		// Save changes.
		this.container.on( changeAction, 'input', function() {
			control.setting.set( jQuery( this ).val() );
		});
	}

});
