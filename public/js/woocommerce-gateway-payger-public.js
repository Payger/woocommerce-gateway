(function( $ ) {
	'use strict';

    //needed since payment options are added to the DOM after the document ready
    jQuery(document).ajaxComplete(function () {


        //Change currency
        $('#payger_gateway_coin').change(function () {

            var $choosen_currency = $(this).val();

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
                    amount: 100,
                }),

                beforeSend: function() {

                   //init loading
                },

                success: function( response, textStatus, jqXHR ){

                    var rate = response.data.rate;
                    var amount = response.data.amount;


                    console.log( 'RESPONSE -> ' );
                    console.log( rate );
                    console.log( amount );

                    $('.payger_amount').html( amount );
                    $('.payger_rate').html( rate );

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
