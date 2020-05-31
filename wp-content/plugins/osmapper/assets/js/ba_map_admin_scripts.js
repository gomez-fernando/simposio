(function ($) {

    var notice = iziToast.settings({
        message: ba_map_ajax.baloonMessage,
        theme: 'dark', // dark
        position: 'bottomCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
        closeOnEscape: true,
        closeOnClick: true,
        pauseOnHover: true,
        progressBarColor: '#a1c45a',
        backgroundColor: '#88a0b9',
        color: '#000',
        displayMode: 2,
        messageSize: 16,
    });


    // console.log('ba_map_admin_scripts.js works!');
    var ajaxAdmin = ba_map_ajax.url;
    $(document).ready(function () {
        $('.repeater').repeater({
            // (Optional)
            // start with an empty list of repeaters. Set your first (and only)
            // "data-repeater-item" with style="display:none;" and pass the
            // following configuration flag
            initEmpty: false,
            defaultValues: {
                latitude: 0,
                longitude: 1
                ,

            },
            // (Optional)
            // "show" is called just after an item is added.  The item is hidden
            // at this point.  If a show callback is not given the item will
            // have $(this).show() called on it.
            show: function () {
                $(this).slideDown();
                iziToast.show({});

                $(this).find('.config ').find('.pins .config_items input.--without_label')[0].checked = true;





            },
            // (Optional)
            // "hide" is called when a user clicks on a data-repeater-delete
            // element.  The item is still visible.  "hide" is passed a function
            // as its first argument which will properly remove the item.
            // "hide" allows for a confirmation step, to send a delete request
            // to the server, etc.  If a hide callback is not given the item
            // will be deleted.
            hide: function (deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            // (Optional)
            // You can use this if you need to manually re-index the list
            // for example if you are using a drag and drop library to reorder
            // list items.
            // ready: function (setIndexes) {
            //     $dragAndDrop.on('drop', setIndexes);
            // },
            // (Optional)
            // Removes the delete button from the first list item,
            // defaults to false.
            isFirstItemUndeletable: true
        })
    });

    /**
     * Show config
     */
    $(document).on('click', '.modalTrigger', function (event) {
        event.preventDefault();

        var config = $(this)[0].offsetParent.nextElementSibling.nextElementSibling;
        $(this).toggleClass('clicked');
        config.classList.toggle('show');


    });


    /**
     * Fire message on every change config item
     */
    $(document).on('change', '.config_items input', function () {

        //TODO: Reload map

        iziToast.show({});

    });


    $(document).on('click', 'a.delete_map', function (e) {
        e.preventDefault();

        let postID = $(this).data('post');

        /**
         * Delete map with given post ID
         */
        sendRequest('delete_map', postID)


    });


    /**
     *
     * @param type
     * @param param
     */
    function sendRequest(type, param) {

        $.ajax({
            url: ajaxAdmin,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'ba_map_ajax_handler',
                requestParams: param,
                requestType: type,
            },
            beforeSend: function () {

            },
            success: function (result) {
                 console.log(result);

            }
        });
    }

    $(document).ready(function () {


        $('input[data-name="latitude"], input[data-name="longitude"]').keypress(validateNumber);


    });

    function validateNumber(event) {
        var key = window.event ? event.keyCode : event.which,
            newValue = event.currentTarget.value.replace(",", ".");

        event.currentTarget.value = newValue;


        if (event.keyCode === 8 || event.keyCode === 46) {
            return true;
        } else if (key < 48 || key > 57) {
            return false;
        } else {
            return true;
        }


        // event.currentTarget.value = newValue;

    };


    /**
     * Tabs in config
     */
    $(document).on('click', '.tabs-headers a', function (e) {
        e.preventDefault();

        let clickedItem = $(this).attr('href');

        /**
         * Show clicked item in headers
         */
        $(this).parent().parent('.tabs-headers').find('li').removeClass('active');
        $(this).parent().addClass('active');

        /**
         * Show clicked item in content
         */
        $(this).parent().parent('.tabs-headers').siblings('.tabs-content').find('.inputHolder ').removeClass('active');
        // $('.inputHolder').removeClass('active');
        $(clickedItem).addClass('active');

    });


})(jQuery);