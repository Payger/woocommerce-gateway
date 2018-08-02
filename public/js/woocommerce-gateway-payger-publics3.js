(function( $ ) {
    'use strict';

    var $rate;
    var $amount;
    var $choosen_currency;
    var processing = false;
    var processing_get_quote = false;


    //handle qrCode text copy
    $( '.copy_clipboard' ).on( 'click', function(){
        /* Get the text field */
        var copyText = document.getElementById("qrCode_text");

        console.log('aqui');
        console.log(copyText);

        /* Select the text field */
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");
    } );

    //Copy for the modal
    $('.copy-item span').on('click', function() {

        var msg = window.prompt("Copy this address", $('#address').val() );
    });



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
        if( $choosen_currency != 0 ) {
            return handle_place_order();
        }
    });


    function handle_place_order() {
        if( processing ) {
            return true;
        }



        return true;
    }

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


    // Can't do this on handle_place_order since we need this to redirect
    // to pay-order page where order id is already created and we can properly
    // generate payment.
    if( $('body').hasClass('woocommerce-order-pay') ) {
        //trigger the modal on order pay page.
        $("#modal").trigger("click");
    }



    //Sets modal timer with 15 min countdown
    if( $('.timer-row__time-left').length ) { // I am at the modal

        var counting          = false;
        var order_min_counter = 'minutes_counter_'  + $('.order_id').val();
        var order_sec_counter = 'seconds_counter_'  + $('.order_id').val();


        //Init to 15 minutes
        if ( null == localStorage.getItem(order_min_counter) ) {
            localStorage.setItem(order_min_counter, 15);
        }
        if ( null == localStorage.getItem(order_sec_counter) ) {
            localStorage.setItem(order_sec_counter, 0);
        }

        var minutesx = parseInt( localStorage.getItem(order_min_counter) );
        var secondsx = parseInt( localStorage.getItem(order_sec_counter) );


        if ( null != minutesx && minutesx > 0 ) {
            //we hate still minutes to process the payment
            counting = true;
        } else {
            //payment expired
            $('.timer-row__time-left').html("0:0");
            $('bp-spinner').hide();
            $('.timer-row__message').hide();
            $('.timer-row__message.error').show();
            $('.top-header .timer-row').addClass('error');
        }


        var end = new Date();

        end.setMinutes(end.getMinutes() + minutesx);
        end.setSeconds(end.getSeconds() + secondsx);

        console.log(end);

        var countDownDate = end.getTime();


        // Update the count down every 1 second
        if( counting ) {
            var x = setInterval(function () {

                console.log('UPDATE COUNT');

                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now an the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                localStorage.setItem(order_min_counter, minutes);
                localStorage.setItem(order_sec_counter, seconds);


                // Display the result in the element with id="demo"
                $('.timer-row__time-left').html(localStorage.getItem(order_min_counter) + ":" + localStorage.getItem(order_sec_counter));

                // If the count down is finished, write some text
                if (distance < 0) {
                    counting = false;
                    clearInterval(x);
                    $('.timer-row__time-left').html("0:0");
                    $('bp-spinner').hide();
                    $('.timer-row__message').hide();
                    $('.timer-row__message.error').show();
                    $('.top-header .timer-row').addClass('error');
                }
            }, 1000);
        }


        //check order status each minute
        var y = setInterval(function () {

            var order_id = $('.order_id').val();

            console.log('check order status for ' + order_id );

            //check order status

            $.ajax({

                cache: false,
                url: payger.ajaxurl,
                type: "get",
                data: ({
                    nonce:payger.nonce,
                    action:'check_order_status',
                    order_id : order_id
                }),

                success: function( response, textStatus, jqXHR ){
                    console.log('response');
                    console.log(response);

                    //order with status processing so lets
                    //update view and stop
                    if( 'processing' == response ){
                        clearInterval(y); //do not check for status again

                        //redirect to thank you page
                    }

                }

            });

        }, 60000);


    }


})( jQuery );