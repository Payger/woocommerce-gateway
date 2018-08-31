(function( $ ) {
    'use strict';

    var $rate;
    var $amount;
    var $choosen_currency;
    var processing = false;
    var processing_get_quote = false;
    var order_id = $('.order_id').val();  //order_id = false para async
    var checkout_form = $( 'form.checkout' );

    //handle qrCode text copy
    $( '.copy_clipboard' ).on( 'click', function(){
        /* Get the text field */
        var copyText = document.getElementById("qrCode_text");

        /* Select the text field */
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");
    } );

    //Copy for the modal
    $('.copy-item span').on('click', function() {
        var msg = window.prompt("Copy this address", $('#address').val() );
    });



    // choose crypto currency on my-account trigger pay
    $('#payger_gateway_coin').change(function () {
        $('#payger_convertion').addClass('hide');
        $('.warning').addClass('hide');
        $choosen_currency = $(this).val();

        if( $choosen_currency != 0 && ! processing_get_quote ) {
            handle_currency_selection($choosen_currency);
        }

    });

    //needed since payment options are added to the DOM after the document ready
    //choose crypto currency on checkout page
    jQuery(document).ajaxComplete(function () {

        //Change currency
        $('#payger_gateway_coin').change(function ( ) {


            $('#payger_convertion').addClass('hide');
            $('.warning').addClass('hide');

            $choosen_currency = $(this).val();
            if( $choosen_currency != 0 && ! processing_get_quote ) {
                handle_currency_selection($choosen_currency);
            }
        });

    });


    // Handle Place Order
    // Place Order Needs to get a new quote in case rate changed

    //Stop processing if an error occured
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

        if( is_synchronous_payment() ) {
            return true;
        }

        // Not Syncronous payment so we need to do process the payment.
        // which means verify with the user the exchange rate


        if( processing ) {
            return true; //avoids more than on call to place order.
        }

        //needed for order-pay endpoint
        if ( $('#order_review').length ) {
            //if ( 0 != $('.order_id').val()) {
            //    $order_id = $('.order_id').val();
            //}
            checkout_form =  $('#order_review');
        }

        checkout_form.block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });

        console.log('ORDER ID ' + order_id );

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
                order_id: order_id
            }),

            success: function( response, textStatus, jqXHR ){

                if( response.success ) {

                    var update_rate = response.data.rate;
                    var update_amount = response.data.amount;

                    if ($rate !== update_rate) {
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

    function handle_currency_selection( $choosen_currency ) {

        if( processing_get_quote ) {
            return;
        }

        processing_get_quote = true;
        var $form            = $('.woocommerce-checkout');

        //hides convertion rates from previous currency
        $('#payger_convertion').addClass('hide');
        $('.warning').addClass('hide');


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
                    $('.currency').html($choosen_currency);

                    setTimeout(function () {
                        $('#payger_convertion').removeClass('hide');
                        $('.warning').removeClass('hide');
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


    // Can't do this on handle_place_order since it redirects
    // to pay-order page where order id is already created and we can properly
    // generate payment.
    if( $('body').hasClass('woocommerce-order-pay') ) {
        //trigger the modal on order pay page if no errors
        if( ! $('.woocommerce-error')[0] ) {
            $("#modal").trigger("click");
        }
    }



    // Sets modal timer with 15 min countdown
    // Checks Payment status so that we notify the buyer
    if( is_synchronous_payment() ) { // I am at the modal

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
            //we have still minutes to process the payment
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
        var countDownDate = end.getTime();

        // Update the count down every 1 second
        if( counting ) {
            var x = setInterval(function () {

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
                // and cancel the order due to missing payment.
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


        // check order status each minute
        // cancels if expires, or redirect to thank you page if
        // payment is detected
        var y = setInterval(function () {

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

                    if( response.success ) {

                        var status        = response.data.status;
                        var url           = response.data.thank_you_url;
                        var payger_status = response.data.payger_status;
                        var address       = response.data.address;
                        var qrcode        = response.data.qrcode;
                        var amount        = response.data.amount;
                        var currency      = response.data.currency;

                        //order with status processing so lets
                        //update view and stop
                        if( 'processing' == status ) {
                            clearInterval(y); //do not check for status again
                            //redirect to thank you page
                            window.location.href = url;
                        }

                        // Needs to update message
                        // Refresh qrCode and Address
                        if( 'UNDERPAID' == payger_status ) {
                            $('.timer-row__message').hide();
                            $('.timer-row__message.underpaid').show();
                            $('.top-header .timer-row').addClass('underpaid');

                            //update data for user
                            $('#address').val(address);
                            $('.payment__scan__qrcode img').attr('src', 'data:image/gif;base64,'+qrcode);
                            $('.amount').html( amount + ' ' + currency );

                        }

                    }
                }

            });

        }, 60000);


    }


    /**
     * Checks weather this is a synchronous or asynchronous payment
     * Verifies if there is a class only present on the modal
     * @returns {boolean}
     */
    function is_synchronous_payment() {
        if( $('.timer-row__time-left').length ) {
            return true;
        }
        return false;
    }


})( jQuery );