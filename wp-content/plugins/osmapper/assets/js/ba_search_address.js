(function ($) {

    /**
     * Search address
     */
    $(document).on('keyup', 'input[data-name="street"]', throttle(function (event) {
        searchAddress($(this))
    }, 1200));

    /**
     * Choose suggestion
     */
    $(document).on('click', '.suggestionAddress', function (e) {
        e.preventDefault();
        let magicKey = $(this).data('magickey'),
            suggestionEl = $(this);


        var addressPromise = new Promise(function (resolve, reject) {
            let searchQuery = 'https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates?outSr=4326&forStorage=false&outFields=*&maxLocations=5&magicKey=' + magicKey + '&f=json';

            $.get(searchQuery, function (data) {
                resolve(data);
            })

        }).then(function (value) {
            let rowID = suggestionEl.parent().parent().parent().parent().parent().find('input[data-name="row_id"]');
            setAddress({
                lat: value.candidates[0].attributes.DisplayY,
                lng: value.candidates[0].attributes.DisplayX,
                street: value.candidates[0].attributes.StAddr,
                city: value.candidates[0].attributes.District,
                full: value.candidates[0],
            }, rowID);

            closeSearchSuggestions();

        }).catch(function (reason) {
//            console.log(reason)
        });
    });

    function closeSearchSuggestions() {
        $('#searchResults').remove()
    }

    function setAddress(address, rowID) {

        let latitude = rowID.siblings('input[data-name="latitude"]'),
            longitude = rowID.siblings('input[data-name="longitude"]'),
            city = rowID.siblings('input[data-name="city"]'),
            streetName = rowID.siblings('input[data-name="street"]');

        latitude.val(address.lat);
        longitude.val(address.lng);
        city.val(address.city);
        streetName.val(address.street);


//        console.log({address, rowID});

    }

    /**
     *
     * Appends results to search box
     *
     * @param results
     */
    function appendResults(results) {

        let resultsList = $('#searchResults ul'),
            html = '';

        if (results.length > 0) {
            results.forEach(function (value) {
                html += '<li><a class="suggestionAddress" href="#" data-magicKey="' + value.magicKey + '">' + value.text + '</a></li>'
            });
        }

        $(document).on('click', function (e) {

            if (e.srcElement.getAttribute('id') === 'ba_map_lat_long') {
                closeSearchSuggestions()
            }
        });

        resultsList.html(html);
    }

    /**
     * Get suggestions by given value
     *
     *
     * @param el
     */
    function searchAddress(el) {

        let parentEl = el.parent(),
            address = el.val(),
            searchResults = $('#searchResults');

        if (parentEl.find('#searchResults').length === 0) {
            parentEl.append('<div id="searchResults"><ul class="results"></ul></div>')
        }

        // console.log(address);

        var promise = new Promise(function (resolve, reject) {


            let searchQuery = 'https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/suggest?text=' + address + '&maxSuggestions=5&f=json';

            $.get(searchQuery, function (data) {
                resolve({
                    address, data
                });
            })

        }).then(function (value) {
            // console.log(value);

            appendResults(value.data.suggestions)

        }).catch(function (reason) {
            // console.log(reason)
        });

        // appendResults(testResults);


    }

    /**
     *
     * Helper function limit recuring functions
     *
     * @param fn
     * @param threshhold
     * @param scope
     * @returns {Function}
     */
    function throttle(fn, threshhold, scope) {
        threshhold || (threshhold = 250);
        let last,
            deferTimer;
        return function () {
            let context = scope || this;

            let now = +new Date,
                args = arguments;
            if (last && now < last + threshhold) {
                // hold on to it
                clearTimeout(deferTimer);
                deferTimer = setTimeout(function () {
                    last = now;
                    fn.apply(context, args);
                }, threshhold);
            } else {
                last = now;
                fn.apply(context, args);
            }
        };
    }


})(jQuery);