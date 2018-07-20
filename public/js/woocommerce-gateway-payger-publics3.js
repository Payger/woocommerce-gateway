(function( $ ) {
    'use strict';

    var $rate;
    var $order_id = false;
    var $amount;
    var $choosen_currency;
    var processing = false;
    var processing_get_quote = false;

    var iframe = document.createElement('iframe');
    iframe.name = 'bitpay';
    iframe.class = 'bitpay';
    iframe.setAttribute('allowtransparency', 'true');
    iframe.style.display = 'none';
    iframe.style.border = 0;
    iframe.style.position = 'fixed';
    iframe.style.top = 0;
    iframe.style.left = 0;
    iframe.style.height = '100%';
    iframe.style.width = '100%';
    iframe.style.zIndex = '2147483647';

    //handle qrCode text copy
    $( '.copy_clipboard' ).on( 'click', function(){
        /* Get the text field */
        var copyText = document.getElementById("qrCode_text");

        /* Select the text field */
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");
    } );




    // choose cryptocurrency on my-account trigger pay
    $('#payger_gateway_coin').change(function () {
        $('#payger_convertion').addClass('hide');
        $choosen_currency = $(this).val();
    });

    //needed since payment options are added to the DOM after the document ready
    //choose cryptocurrency on checkout page
    jQuery(document).ajaxComplete(function () {

        //Change currency
        $('#payger_gateway_coin').change(function ( ) {


            $('#payger_convertion').addClass('hide');

            $choosen_currency = $(this).val();
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
        alert('handle place order xx');
        if( processing ) {
            return true;
        }


       //  $( "#modal" ).trigger( "click" );


      /*  checkout_form.block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });*/

        return true;
    }



})( jQuery );