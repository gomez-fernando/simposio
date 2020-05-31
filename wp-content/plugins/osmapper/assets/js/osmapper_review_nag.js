/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */
(function ($) {

    $(document).on('click', '.ba_map_notice .notice-dismiss, .ba_map_notice .dismiss-nag', function (e) {
        e.preventDefault();

		$.ajax({
            url: ajaxAdmin,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'ba_map_ajax_handler',
                requestType: 'close_review',
            },
            beforeSend: function () {
                mapHolder.addClass('loading');
            },
            success: function (result) {
                $('.ba_map_notice').remove();
            }
        });
    });


})(jQuery);
