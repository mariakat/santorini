jQuery(document).ready(function ($) {

    //Replace Text
    let cproductID = $('.single-product .single_add_to_cart_button').val();
    console.log(cproductID);
    if(cproductID != 'undefined'){
        if(cproductID == 1552){
            setTimeout(function(){
                $('#text-1635150323641').attr('value','8:30-14:00');
                $('#text-1635150323641').val('8:30-14:00').trigger(jQuery.Event('keypress', {keycode: 32}));
            },200);
        }else if(cproductID == 1558){
            setTimeout(function(){
                $('#text-1635150323641').attr('value','10:30-After Sunset');
                $('#text-1635150323641').val('10:30-Sunset').trigger(jQuery.Event('keypress', {keycode: 32}));
            },200);
        }else if(cproductID == 1555){
            setTimeout(function(){
                $('#text-1635150323641').attr('value','15:00-After Sunset');
                $('#text-1635150323641').val('15:00-After Sunset').trigger(jQuery.Event('keypress', {keycode: 32}));
            },200);
        }
    }
    $('.single_add_to_cart_button').prop('disabled', true);
    let isProduct = BookObj.isProduct;
    let productID = BookObj.currentID;
    $('.wcpa_datepicker').after('<span class="dateLoading" style="color: orange; font-size: 14px;"></span>');
    $('.wcpa_datepicker:not(#date-1635118336615)').on('change', function () {
        $('.dateLoading').empty();
        $('.dateLoading').html('<span class="dateLoading" style="color: orange; font-size: 14px;">Checking Availability</span>');
        jQuery.ajax({
            type: 'POST',
            url: BookObj.ajaxURL,
            data: {
                'action': 'santorini_validate_booking',
                'date': $(this).val(),
                'cruiseID': productID
            },
            dataType: 'json',
            success: function (data) {
                console.log(data);

                if (data.alreadyBooked === true) {
                    $('.dateLoading').html('<span class="dateLoading" style="color: red; font-size: 14px;">Please Choose a different date</span>');
                    $('.single_add_to_cart_button').prop('disabled', true);
                } else {
                    $('.dateLoading').html('<span class="dateLoading" style="color: green; font-size: 14px;">Available!</span>');
                    $('.single_add_to_cart_button').prop('disabled', false);
                }
            },
            error: function (ts) {

            }
        });
    });


    /** New Booking check **/
    $('#date-1635118336615').on('change', function () {
        $('.dateLoading').empty();
        $('.dateLoading').html('<span class="dateLoading" style="color: orange; font-size: 14px;">Checking Availability</span>');
        jQuery.ajax({
            type: 'POST',
            url: BookObj.ajaxURL,
            data: {
                'action': 'santorini_last_booking',
                'date': $(this).val(),
                'cruiseID': productID

            },
            dataType: 'json',
            success: function (data) {
                console.log(data);

                if (data.alreadyBooked === true) {
                    $('.dateLoading').html('<span class="dateLoading" style="color: red; font-size: 14px;">Please Choose a different date</span>');
                    if(data.alternativeMSG != ''){
                        $('.dateLoading').html('<div class="alternative-days" style="color: red; font-size: 14px;">'  + data.alternativeMSG + '</div>');
                    }
                    jQuery('#number-1668757666560').val('').trigger(jQuery.Event('keypress', {keycode: 32}));
                    jQuery('#number-1668757666560').trigger('change');
                    $('.single_add_to_cart_button').prop('disabled', true);
                } else {
                    $('.dateLoading').html('<span class="dateLoading" style="color: green; font-size: 14px;">Available!</span>');
                    jQuery('#number-1668757666560').val(data.boat).trigger(jQuery.Event('keypress', {keycode: 32}));
                    jQuery('#number-1668757666560').trigger('change');
                    // Apply discount
                    if(data.discount !== 1 && data.discount != null){
                        jQuery('#number-1668685233069').val(data.discount).trigger(jQuery.Event('keypress', {keycode: 32}));
                        jQuery('#number-1668685233069').trigger('change');
                        let discountStr = (data.discount)*100;
                        $('.dateLoading').html('<span class="dateLoading" style="color: green; font-size: 14px;">' +
                            'Available and you are eligible for a discount of <span style="font-weight: bold;">'+discountStr+'% OFF</span>!</span>');

                    }else{
                        jQuery('#number-1668685233069').val(0).trigger(jQuery.Event('keypress', {keycode: 32}));
                        jQuery('#number-1668685233069').trigger('change');
                    }
                    $('.single_add_to_cart_button').prop('disabled', false);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);

            }
        });
    });

    // jQuery('.wcpa_datepicker').on('change', function () {
    //    const rndInt = Math.floor(Math.random() * 15) + 1
    //     jQuery('#hidden-1668556539294').val(rndInt);
        //jQuery('#number-1668685233069').val(rndInt);
        // jQuery('#number-1668685233069').val(rndInt).trigger(jQuery.Event('keypress', {keycode: 32}));
        // jQuery('#number-1668685233069').trigger('change');

        // jQuery("#number-1668685233069").on("change", function() {
        //   console.log('add');
        // });

    // });


});