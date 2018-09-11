jQuery( document ).ready( function() {

  /* If there are required actions, add an icon with the number of required actions in the About mosh page -> Actions required tab */
  var mosh_nr_actions_required = moshWelcomeScreenObject.nr_actions_required,
      context = jQuery( '.widget-content' ),
      sliders = context.find( '.slider-container' ),
      slider, input, inputId, id, min, max, step;

  if ( (typeof mosh_nr_actions_required !== 'undefined') && (mosh_nr_actions_required != '0') ) {
    jQuery( 'li.mosh-w-red-tab a' ).append( '<span class="mosh-actions-count">' + mosh_nr_actions_required + '</span>' );
  }

  /* Dismiss required actions */
  jQuery( '.mosh-required-action-button' ).click( function() {

    var id = jQuery( this ).attr( 'id' ),
        action = jQuery( this ).attr( 'data-action' );
    jQuery.ajax( {
      type: 'GET',
      data: { action: 'mosh_dismiss_required_action', id: id, todo: action },
      dataType: 'html',
      url: moshWelcomeScreenObject.ajaxurl,
      beforeSend: function( data, settings ) {
        jQuery( '.mosh-tab-pane#actions_required h1' ).
            append( '<div id="temp_load" style="text-align:center"><img src=' + moshWelcomeScreenObject.template_directory +
                '"/inc/libraries/welcome-screen/img/ajax-loader.gif" /></div>' );
      },
      success: function( data ) {
        location.reload();
        jQuery( '#temp_load' ).remove();
        /* Remove loading gif */
      },
      error: function( jqXHR, textStatus, errorThrown ) {
        console.log( jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown );
      }
    } );
  } );

  function mosh_rangesliders_init() {
    jQuery.each( sliders, function() {
      var slider = jQuery( this ).find( '.ss-slider' ),
          input = jQuery( this ).find( '.rl-slider' ),
          inputId = input.attr( 'id' ),
          id = slider.attr( 'id' ),
          min = jQuery( '#' + id ).attr( 'data-attr-min' ),
          max = jQuery( '#' + id ).attr( 'data-attr-max' ),
          step = jQuery( '#' + id ).attr( 'data-attr-step' );

      jQuery( '#' + id ).slider( {
        value: jQuery( '#' + inputId ).attr( 'value' ),
        range: 'min',
        min: parseFloat( min ),
        max: parseFloat( max ),
        step: parseFloat( step ),
        /**
         * Removed Change event because server was flooded with requests from
         * javascript, sending changesets on each increment.
         *
         * @param event
         * @param ui
         */
        slide: function( event, ui ) {
          jQuery( '#' + inputId ).attr( 'value', ui.value );
        },
        /**
         * Bind the change event to the "actual" stop
         * @param event
         * @param ui
         */
        stop: function( event, ui ) {
          jQuery( '#' + inputId ).trigger( 'change' );
        }
      } );

      jQuery( input ).on( 'focus', function() {
        jQuery( this ).blur();
      } );

      jQuery( '#' + inputId ).attr( 'value', ( jQuery( '#' + id ).slider( 'value' ) ) );
      jQuery( '#' + inputId ).on( 'change', function() {
        jQuery( '#' + id ).slider( {
          value: jQuery( this ).val()
        } );
      } );
    } );
  };
  mosh_rangesliders_init();
  jQuery( document ).ajaxStop( function() {
    mosh_rangesliders_init();
  } );
} );