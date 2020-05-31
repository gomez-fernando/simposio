/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


(function ($) {


    var ajaxAdmin = ba_map_ajax.url,
        loadingGif = ba_map_ajax.gif,
        defaultConfig = ba_map_ajax.defaultConfig,
        mapHolder = $('.ba_map_holder');

    if (mapHolder.length > 0) {

        // console.log('mapHolder present')

        $.each(mapHolder, function (key, value) {

            var mapID = $(this).data('map-id'),
                holderID = $(this).attr('id');
            // console.log(holderID);


            /**
             * Whenever we have custom hard-coded html map
             */
            if ($(this).hasClass('markers')) {

                var mapConfig = findMarkers($(this)),
                    holderID = generateID();

                //Attach generated ID to this div
                $(this).attr('id', holderID);
                renderCustomMap(holderID, mapConfig);
            } else {
				
                sendRequest('getConfig', {
                    'mapID': mapID,
                    'holderID': holderID,
                });
            }
        })
    }


    $(document).ajaxSuccess(function (event, xhr, param) {
        /**
         * Prevent catching WordPress ajax actions like heartbeat
         */
//		console.log(param.data);
		if( param.hasOwnProperty( 'data' ) ) {
//			console.log( param.data );
		    if( param.data.indexOf( 'ba_map_ajax_handler' ) > 0 ) {
			    if (param && typeof param.data !== "undefined") {
				   let sentParams = parseString(param.data);
				   // console.log(sentParams);
				   /**
					* Make sure that we get data from ba_map_ajax_handler handler
					*/
				    if (sentParams.action === "ba_map_ajax_handler") {
					   /**
						* Check sent request type to verify sent object
						*/
						   // console.log(typeof xhr.responseJSON);
					    var markers,
						   config;


					    if (typeof xhr.responseJSON !== 'undefined' && sentParams.requestType === 'getConfig') {

						   markers = xhr.responseJSON.markers;
						   config = xhr.responseJSON.config;

						   // console.log(xhr.responseJSON);
						   // console.log(config);

						   renderMap(markers, config, xhr.responseJSON)
					    } else if (typeof xhr.responseText !== 'undefined' && sentParams.requestType === 'getConfig') {

						   var responseJson = JSON.parse(xhr.responseText);

						   markers = responseJson.markers;
						   config = responseJson.config;

						   renderMap(markers, config, responseJson);
						   // console.log(responseJson);
					    } else {
//						   console.log("Couldn't recieve response")
					    }
				   }
			    }
		    }
	   }
    });

    /**
     * Scans div for markers to add
     *
     * @param obj
     */
    function findMarkers(obj) {

        if (!obj.hasClass('markers')) {
            return false;
        }

        var markers = {
            markers: {},
            config: {},
        };
        /**
         * Find all markes inside given div
         */
        $.each(obj.find('.marker'), function (key, value) {

            let lat = $(this).data('lat'),
                lng = $(this).data('lng'),
                pin = $(this).data('pin'),
                infoWindow = $(this)[0].innerHTML,
                obj = {};


            if (typeof lat !== "undefined" && typeof lng !== "undefined") {

                obj['lat'] = lat;
                obj['lng'] = lng;
                if (infoWindow !== "") {
                    obj['infoWindow'] = infoWindow.replace('<p></p>', '');
                }
                /**
                 * Set custom or default map pin
                 */
                if (typeof pin !== "undefined") {
                    obj['icon'] = pin;
                } else {
                    obj['icon'] = defaultConfig.pin;
                }

                //Add found items to master obj
                markers['markers'][key] = obj;
            }


        });

        /**
         * Add all attached data-params
         */
        $.each(obj[0].dataset, function (key, value) {

            // console.log(key);

            if (value !== "" && key !== "") {
                markers['config'][key] = value;
            }

        });


        return markers;
    }

    function isMobile() {
        var check = false;
        (function (a) {
            if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true;
        })(navigator.userAgent || navigator.vendor || window.opera);
        return check;
    };

    function generateID() {

        return '_' + Math.random().toString(36).substr(2, 9);
    }

    /**
     * Generate Custom map from given settings in HTML
     * @param mapID
     * @param settings
     * @returns {*}
     */
    function renderCustomMap(mapID, settings) {

        var mapMarkers = [],
            allLatLngs = [];


        /**
         * Color scheme of map
         * colors :
         * normal: 'http://{s}.tile.osm.org/{z}/{x}/{y}.png'
         * gray:' http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png'
         * @type {mn}
         */
        var tileLayer = new L.TileLayer(settings.config.scheme + '/{z}/{x}/{y}.png', {
            attribution: '<a href="https://b4after.pl">Interactive agency Before After </a>&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        });


        /**
         * Main instance of map
         * @type {be}
         */
        var map = new L.Map(mapID, {
            center: [52.3735144, 16.9020508],
            // minZoom: 20,
            // maxZoom: 30,
            zoom: 15,
            scrollWheelZoom: settings.config.scroll === "false" ? false : true, //Enable or disable scroll on map
            tap: false,
            dragging: !isMobile(),
            layers: [tileLayer],
//			fullscreenControl: {
//				pseudoFullscreen: false
//			},
//			fullscreenControl: true,
        });

//		map.addControl(new L.Control.Fullscreen());
        /**
         * Render each marker in the map
         */
        $.each(settings.markers, function (key, value) {

            var marker = $(this);

            /**
             * Generate array of all coordinates to center it in further step
             * @type {*[]}
             */
            allLatLngs[key] = [value.lat, value.lng];

            /**
             * New pin instance
             */
            var customIcon = L.icon({
                iconUrl: value.icon,
                iconSize: [38, 38],
                popupAnchor: [-3, -20]
            });

            /**
             * New marker instance
             */
            var mapMarker = L.marker([value.lat, value.lng], {
                icon: customIcon,

            });
            /**
             * Generate infobox
             */
            if (typeof value.infoWindow !== 'undefined' && value.infoWindow !== "") {
                /**
                 * # 1.3.4
                 * Fixed error with no enters in javascript
                 */
                mapMarker.bindPopup('<p class="markerInfoBox">' + value.infoWindow.replace(/(\r\n|\n|\r)/gm, "<br />") + '</p>', {
                    className: 'osmapper_class'
                })
            }
            mapMarkers[key] = mapMarker;
            //Add marker to map
            mapMarker.addTo(map)


        });
        /**
         * Get center of all lat lngs
         * @type {T}
         */
            // console.log(allLatLngs);
        var bounds = new L.LatLngBounds(allLatLngs);
        /**
         * Center on all markers
         */
        map.fitBounds(bounds);

        if (allLatLngs.length < 2) {
            map.setZoom(14);
        }

        return settings;
    }

    /**
     *
     * @param markers
     * @param config
     * @param xhrOptions
     */
    function renderMap(markers, config, xhrOptions) {
        let mapID = xhrOptions.params.holderID,
            mapMarkers = [],
            allLatLngs = [],
            mapScheme;

        if (config.pin) {
            var legacyIcon = L.icon({
                iconUrl: config.pin, //TODO: dodać stadardowy url dla kompatybilności
                iconSize: [38, 38],
                popupAnchor: [-3, -20]
            });
        }
        if (typeof config.layer === "undefined") {
            mapScheme = '//basemaps.cartocdn.com/light_all/';
        } else {
            mapScheme = config.layer
        }

        if (config.map_height) {
            $('#' + mapID).css({
                'height': config.map_height + 'px'
            });
        }


        /**
         * Color scheme of map
         * colors :
         * normal: 'http://{s}.tile.osm.org/{z}/{x}/{y}.png'
         * gray:' http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png'
         * @type {mn}
         */
        let tileLayer = new L.TileLayer(mapScheme + '{z}/{x}/{y}.png', {
            attribution: '<a href="https://b4after.pl/osmapper?utm_source=' + window.location.href + '&utm_medium=map_contributors&utm_campaign=OSMapper">Agencja Interaktywna BEFORE AFTER </a> &copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        });


        let map = new L.Map(mapID, {
            center: [52.3735144, 16.9020508],
            // minZoom: 20,
            // maxZoom: 30,
            scrollWheelZoom: config.zoom_on_scroll === "No" ? false : true, //Enable or disable scroll on map
            tap: false,
            dragging: !isMobile(),
            layers: [tileLayer],
			fullscreenControl: config.full_screen === "No" ? false : true, //Enable or disable scroll on map,
        });


        /**
         * Debuggggg
         */
        // console.log({
        //     config, xhrOptions
        // });

        $.each(markers, function (key, value) {
            let rowID = value.row_id;
            /**
             * Set default rowID if map isnt saved after update
             *
             */
            if (typeof rowID === "undefined") {
                rowID = $('input[data-name="row_id"]').attr('id');
            }

            let locationsLat = $('#' + rowID).siblings('input[data-name="latitude"]').attr('id'),
                locationsLng = $('#' + rowID).siblings('input[data-name="longitude"]').attr('id'),
                marker = $(this);


            if (value.pin) {
                var markerIcon = L.icon({
                    iconUrl: value.pin,
                    iconSize: [38, 38],
                    popupAnchor: [-3, -20]
                });
            }


            /**
             * Fixing undefined values of oldeer versions of plugin
             */
            if (typeof locationsLat === "undefined" || typeof locationsLng === "undefined") {
                locationsLat = $('input[value="' + value.latitude + '"]');
                locationsLng = $('input[value="' + value.longitude + '"]');
            }
            /**
             * Generate array of all coordinates to center it in further step
             * @type {*[]}
             */
            allLatLngs[key] = [value.latitude, value.longitude];
            /**
             * New marker instance
             */
            let mapMarker = L.marker([value.latitude, value.longitude], {
                icon: typeof markerIcon !== 'undefined' ? markerIcon : legacyIcon,
                draggable: xhrOptions.is_admin ? true : false,
            });
            /**
             * Generate infobox
             */
            if (typeof value.infobox !== 'undefined' && value.infobox !== "") {

                /**
                 * # 1.3.4
                 * Fixed error with no enters in javascript
                 */
                mapMarker.bindPopup('<div class="markerInfoBox">' + value.infobox.replace(/(\r\n|\n|\r)/gm, "<br />") + '</div>', {
                    className: value.popup_style ? value.popup_style : 'standard'
                })
            }

            mapMarker.on('dragend', function (event) {
                // console.log({
                //     locationsLat,
                //     locationsLng
                // });
                let newPosition = event.target.getLatLng();


                event.target.setLatLng(newPosition, {
                    draggable: 'true'
                }).update();
                /**
                 * Overwrite values in inputs
                 */
                // let lat = $('#' + locationsLat),
                //     lng = $('#' + locationsLng);


                locationsLat.val(newPosition.lat);
                locationsLng.val(newPosition.lng);

                locationsLat.addClass('manuallyDragged');
                locationsLng.addClass('manuallyDragged');


                // console.log(event);
                xhrOptions.is_admin ? iziToast.show({}) : '';
            });


            mapMarkers[key] = mapMarker;
            //Add marker to map
            mapMarker.addTo(map)
        });
        /**
         * Get center of all lat lngs
         * @type {T}
         */
        let bounds = new L.LatLngBounds(allLatLngs);
        /**
         * Center on all markers
         */

        // console.log(bounds);
        // console.log(bounds.getNorth());
        // console.log(bounds.getEast());
        // console.log(bounds.getCenter());
        // map.fitBounds(bounds);

        if (allLatLngs.length < 2) {

            map.setView({
                lat: bounds.getNorth(),
                lng: bounds.getEast(),
            }, config.map_zoom ? config.map_zoom : 14);
            /**
             * Move map to X/Y position
             * @type {number}
             */
            if (config.marker_position !== "center") {
                var offset = map.getSize(),
                    params = [];

                if (config.marker_position === "left") {
                    params = [
                        offset.x * .35, 0
                    ]
                } else if (config.marker_position === "right") {
                    params = [
                        offset.x * -.35, 0
                    ]
                } else if (config.marker_position === "bottom") {
                    params = [
                        0, offset.y * -.35,
                    ]
                } else if (config.marker_position === "top") {
                    params = [
                        0, offset.y * .35,
                    ]
                }
                // console.log(params);
                map.panBy(new L.Point(params[0], params[1]), {animate: true});
            }
        } else {
            map.fitBounds(bounds);
            // map.setView(bounds.getCenter(), 14)
            // console.log(map);
            /**
             * Set new zoom only when new is greated than old one after fitBounds
             */
            let newZoom = map.getZoom() > parseInt(config.map_zoom) ? config.map_zoom : map.getZoom();
            // console.log(newZoom);
            map.setZoom(newZoom);
        }

    }

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
                mapHolder.addClass('loading');
            },
            success: function (result) {
                // console.log(result);
                mapHolder.removeClass('loading');

                // console.log(result);


            }
        });
    }

    /**
     * Javascript version of PHP
     * @param string
     * @returns {Array}
     */
    function parseString(string) {

        /**
         * Convert string to array
         */
        var data = string.split('&');

        var results = [];
        for (var i = 0; i < data.length; i++) {
            /**
             *
             */
            results.push(data[i].split('='));
        }

        var finalResults = [];
        $.each(results, function (k, v) {

            /**
             * Make an associative array
             */
            finalResults[v[0]] = v[1]


        });

        return finalResults;
    }
})(jQuery);
