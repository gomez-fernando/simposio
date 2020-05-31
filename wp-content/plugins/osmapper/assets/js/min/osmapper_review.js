/**
 * Author : Arkadiusz Wawrzyniak
 *
 */
(function ($) {
    
    $(document).on('click', '.ba_map_notice .notice-dismiss, .ba_map_notice .dismiss-nag', function (e) {
        e.preventDefault();
		$.ajax({
            url: ba_map_ajax.url,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'ba_map_ajax_handler',
                requestType: 'close_review',
            },
            success: function (result) {

                $('.ba_map_notice').remove();
            }
        });
    });


})(jQuery);
