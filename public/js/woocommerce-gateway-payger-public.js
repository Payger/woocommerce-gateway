(function( $ ) {
	'use strict';

    var $rate;
    var $order_id = false;
    var $amount;
    var $choosen_currency;
    var processing = false;
    var processing_get_quote = false;


    // choose cryptocurrency on my-account trigger pay
    $('#payger_gateway_coin').change(function () {
        $('#payger_convertion').addClass('hide');
        $choosen_currency = $(this).val();

        if( $choosen_currency != 0 && ! processing_get_quote ) {
            handle_currency_selection($choosen_currency);
        }
    });

    //needed since payment options are added to the DOM after the document ready
    //choose cryptocurrency on checkout page
    jQuery(document).ajaxComplete(function () {

        //Change currency
        $('#payger_gateway_coin').change(function ( ) {


            $('#payger_convertion').addClass('hide');

            $choosen_currency = $(this).val();

            console.log( $choosen_currency);

            if( $choosen_currency != 0 && ! processing_get_quote ) {
                handle_currency_selection($choosen_currency);
            }
        });

    });


    // Handle Place Order
    // Place Order Needs to get a new quote in case rate changed
    var checkout_form = $( 'form.checkout' );

    $( document.body ).on( 'checkout_error', function(){
        processing = false; //we need to double check again if form submitting fails
    } );

    checkout_form.on( 'checkout_place_order', function( e ) {
        if( $choosen_currency != 0 ) {
            return handle_place_order();
        }

    });

    $('form#order_review #place_order').on( 'click', function(e){
        console.log('review place order');
        if( $choosen_currency != 0 ) {
            return handle_place_order();
        }
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
    
    function handle_currency_selection( $choosen_currency ) {

        if( processing_get_quote ) {
            return;
        }

        processing_get_quote = true;
        var $form            = $('.woocommerce-checkout');

        //hides convertion rates from previous currency
        $('#payger_convertion').addClass('hide');


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
                if( response.success ) {

                    $rate = response.data.rate;
                    $amount = response.data.amount;

                    $('.payger_amount').html($amount);
                    $('.payger_rate').html($rate);

                    setTimeout(function () {
                        $('#payger_convertion').removeClass('hide');
                    }, 500);


                    $form.unblock();
                    processing_get_quote = false;
                } else {
                    location.reload(); // shows error message
                    return false;
                }

            },

            error: function( jqXHR, textStatus, errorThrown ){
                $form.unblock();
                processing_get_quote = false;
                console.log( 'The following error occured: ' + textStatus, errorThrown );
            },

            complete: function( jqXHR, textStatus ){
                processing_get_quote = false;
            }

        });
    }

    function handle_place_order() {

        if( processing ) {
            return true;
        }
        //needed for order-pay endpoint
        if ( $('#order_review').length ) {
            if ( 0 != $('.order_id').val()) {
                $order_id = $('.order_id').val();
            }
            checkout_form =  $('#order_review');
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
            url: payger.ajaxurl,
            type: "get",
            data: ({
                nonce:payger.nonce,
                action:'payger_get_quote',
                to: $choosen_currency,
                order_id: $order_id
            }),

            success: function( response, textStatus, jqXHR ){
                console.log(response);

                if( response.success ) {
                    console.log('aqui');

                    var update_rate = response.data.rate;
                    var update_amount = response.data.amount;

                    if ($rate !== update_rate) {

                        console.log('RATES ARE DIFFERENT');

                        $('.update_amount').html(update_amount);
                        $('.update_rate').html(update_rate);
                        $("#dialog").dialog({
                            buttons: [
                                {
                                    text: "OK",
                                    click: function () {
                                        $(this).dialog("close");
                                        // checkout_form.off( 'checkout_place_order');
                                        processing = true;
                                        checkout_form.submit();
                                        return true;
                                    }
                                },
                                {
                                    text: "Cancel",
                                    click: function () {
                                        $(this).dialog("close");
                                        checkout_form.unblock();
                                        processing = false;
                                        return false;
                                    }
                                }
                            ]
                        });
                        //rate changed so lets ask for user confirmation
                    } else {
                        console.log('SAME RATE PROCEED');
                        processing = true;
                        checkout_form.unblock();
                        checkout_form.submit();
                        return true; //rate did not change so lets proceed
                    }
                } else {
                    location.reload(); // shows error message
                    return false;
                }

                checkout_form.unblock();

            },

            error: function( jqXHR, textStatus, errorThrown ){
                console.log( 'The following error occured: ' + textStatus, errorThrown );
                return false;
            }

        });
        return false;
    }


})( jQuery );
