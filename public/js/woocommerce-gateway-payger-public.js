(function( $ ) {
	'use strict';
    //needed since payment options are added to the DOM after the document ready
    jQuery(document).ajaxComplete(function () {

        //Change currency
        $('#payger_gateway_coin').change(function () {

            var $choosen_currency = $(this).val();
            var $form             = $('.woocommerce-checkout');

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

                    var rate = response.data.rate;
                    var amount = response.data.amount;


                    console.log( 'RESPONSE -> ' );
                    console.log( rate );
                    console.log( amount );

                    $('.payger_amount').html( amount );
                    $('.payger_rate').html( rate );

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

})( jQuery );
