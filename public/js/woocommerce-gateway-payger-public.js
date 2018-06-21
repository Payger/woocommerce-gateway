(function( $ ) {
	'use strict';

    var $rate;
    var $amount;
    var $choosen_currency;
    var processing = false;


    // choose cryptocurrency on my-account trigger pay
    $('#payger_gateway_coin').change(function () {

        console.log('click');
        $choosen_currency = $(this).val();
        console.log( $choosen_currency);

        handle_currency_selection( $choosen_currency );
    });

    //needed since payment options are added to the DOM after the document ready
    //choose cryptocurrency on checkout page
    jQuery(document).ajaxComplete(function () {

        //Change currency
        $('#payger_gateway_coin').change(function () {

            console.log(' CHANGE payger_gateway_coin ');

            $choosen_currency = $(this).val();

            handle_currency_selection( $choosen_currency );


        });

    });


    function handle_currency_selection( $choosen_currency ) {

        var $form         = $('.woocommerce-checkout');

        //hides convertion rates from previous currency
        $('#payger_convertion').addClass('hide');

        if( 0 == $choosen_currency ) {
            return;
        }


        var order_key = false;
        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            if ( results ) {
                return results[1]
            } else {
                return 0
            };
        }

        if( $.urlParam('key') ) {
            order_key = $.urlParam('key');
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
                order_key : order_key
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
    }








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



    //handle qrCode text copy
    $( '.copy_clipboard' ).on( 'click', function(){
        /* Get the text field */
        var copyText = document.getElementById("qrCode_text");

        /* Select the text field */
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");
    } );


})( jQuery );
