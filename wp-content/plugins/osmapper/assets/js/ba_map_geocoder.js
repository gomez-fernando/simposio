(function ($) {
    /**
     * To fetch an geolocalization of given addres we are sending request to
     * https://nominatim.openstreetmap.org/search?format=json&q=poznanska+16+poznan+61+694
     * @type {*|HTMLElement}
     */
    var button = $('input.getCoords'),
        locations = {},
        searchGateway = 'https://nominatim.openstreetmap.org/search?format=json&q=';


    function getCoordinates() {
        // Now can loop all items
        $.each($(document).find('.repeaterRow'), function (key, value) {
            // console.log($(this));

            var address = $(this).find('input[data-name="street"]').val(),
                city = $(this).find('input[data-name="city"]').val(),
                zipCode = $(this).find('input[data-name="zip_code"]').val(),
                results,
                dataToSent = [],
                lat = $(this).find('input[data-name="latitude"]'),
                lng = $(this).find('input[data-name="longitude"]');

            if (typeof address !== 'undefined' && address !== '') {
                dataToSent.push(address)
            }
            if (typeof city !== 'undefined' && city !== '') {
                dataToSent.push(city);
            }
            if (typeof zipCode !== 'undefined' && zipCode !== '') {
                dataToSent.push(zipCode)
            }


            lat.addClass('loading');
            lng.addClass('loading');

            results = getCoords(parseString(dataToSent), $(this));

        })
    }

    $(document).on('click', '.getCoords', function (e) {
        e.preventDefault();
        // console.log('btn clicked');


        getCoordinates();
    });


    /**
     * Prepares params to send to open street maps api
     * @param data
     * @returns {string}
     */
    function parseString(data) {

        var url = '';
        url = data.join('+');
        // console.log(url.replace(' ', '+'));


        return url.replace(' ', '+');
    }

    function getCoords(url, object) {

        let lat = object.find('input[data-name="latitude"]'),
            lng = object.find('input[data-name="longitude"]');


        $.ajax({
            url: searchGateway + url,
            type: 'post',
            dataType: 'json',
            async: false,
            beforeSend: function () {


            },
            success: function (result) {

                // console.log(result);
                if (result.length < 1) {
                    // alert('Could not find coordinates for given address. Check for mistakes');

                    //Set default
                    lat.val(52.413685);
                    lng.val(16.9145975);

                    iziToast.show({

                        message: ba_map_ajax.noResultsMessage,
                        theme: 'dark', // dark
                        position: 'bottomCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
                        closeOnEscape: true,
                        closeOnClick: true,
                        pauseOnHover: true,
                        progressBarColor: '#a1c45a',
                        backgroundColor: '#88a0b9',
                        color: '#000',
                        displayMode: 1,
                        messageSize: 16,
                        timeout: 8000,

                    });
                }
                else {
                    iziToast.show({
                        message: ba_map_ajax.baloonMessage,
                        theme: 'dark', // dark
                        position: 'bottomCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
                        closeOnEscape: true,
                        closeOnClick: true,
                        pauseOnHover: true,
                        progressBarColor: '#a1c45a',
                        backgroundColor: '#88a0b9',
                        color: '#000',
                        displayMode: 1,
                        messageSize: 16,
                        timeout: 8000,
                    });


                    lat.val(result[0].lat);
                    lng.val(result[0].lon);

                }

                // setTimeout(function () {
                lat.removeClass('loading');
                lng.removeClass('loading');
                // }, 1000)

            }
        });

    }

})(jQuery);
