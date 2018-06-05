(function( $ ) {
	'use strict';

    var $rate;
    var $amount;
    var $choosen_currency;
    var processing = false;

    //needed since payment options are added to the DOM after the document ready
    jQuery(document).ajaxComplete(function () {

        //Change currency
        $('#payger_gateway_coin').change(function () {

            console.log(' CHANGE payger_gateway_coin ');

            $choosen_currency = $(this).val();
            var $form         = $('.woocommerce-checkout');

            //hides convertion rates from previous currency
            $('#payger_convertion').addClass('hide');

            if( 0 == $choosen_currency ) {
                return;
            }

            //get current rates for this currency
            $.ajax({

                cache: false,
                timeout: 3000,
                url: payger.ajaxurl,
                type: "get",
                data: ({
                    nonce:payger.nonce,
                    action:'payger_get_quote',
                    to: $choosen_currency,
                }),

                beforeSend: function() {

                   //init loading
                    $form.block({
                        message: null,
                        overlayCSS: {
                            background: '#fff',
                            opacity: 0.6
                        }
                    });
                },

                success: function( response, textStatus, jqXHR ){

                    $rate = response.data.rate;
                    $amount = response.data.amount;

                    $('.payger_amount').html( $amount );
                    $('.payger_rate').html( $rate );

                    setTimeout(function(){
                        $('#payger_convertion').removeClass('hide');
                    }, 500);


                    $form.unblock();


                },

                error: function( jqXHR, textStatus, errorThrown ){
                    console.log( 'The following error occured: ' + textStatus, errorThrown );
                },

                complete: function( jqXHR, textStatus ){
                }

            });

        });

    });


    var checkout_form = $( 'form.checkout' );

    $( document.body ).on( 'checkout_error', function(){
        console.log('form fails');
        processing = false; //we need to double check again if form submitting fails
    } );

    checkout_form.on( 'checkout_place_order', function( e ) {

        console.log('processing ' + processing);

        if( processing ) {
            return true;
        }

      //  e.preventDefault();

        // do your custom stuff
        console.log('CLICK ON PLACE ORDER');

        checkout_form.block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });

        //double check if we still have the same array for this currency
        //get current rates for this currency
        $.ajax({

            cache: false,
            timeout: 3000,
            url: payger.ajaxurl,
            type: "get",
            data: ({
                nonce:payger.nonce,
                action:'payger_get_quote',
                to: $choosen_currency,
            }),

            success: function( response, textStatus, jqXHR ){

                var update_rate   = response.data.rate;
                var update_amount = response.data.amount;

                console.log('new rate');
                console.log(update_rate);


                if( $rate !== update_rate ){
                    console.log('RATE CHANGED');
                    $('.update_amount').html( update_amount );
                    $('.update_rate').html( update_rate );
                    $( "#dialog" ).dialog({
                        buttons: [
                            {
                                text: "OK",
                                click: function() {
                                    $( this ).dialog( "close" );
                                   // checkout_form.off( 'checkout_place_order');
                                    processing = true;
                                    checkout_form.submit();
                                    return true;
                                }
                            },
                            {
                                text: "Cancel",
                                click: function() {
                                    $( this ).dialog( "close" );
                                    checkout_form.unblock();
                                    processing = false;
                                    return false;
                                }
                            }
                        ]
                    });
                    //rate changed so lets ask for user confirmation
                } else {
                    console.log('same rate lets proceed');
                    return true; //rate did not change so lets proceed
                }
            },

            error: function( jqXHR, textStatus, errorThrown ){
                console.log( 'The following error occured: ' + textStatus, errorThrown );
                return false;
            }

        });

        return false;
    });

})( jQuery );
