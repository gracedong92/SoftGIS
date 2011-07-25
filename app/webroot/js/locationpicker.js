(function( $ ) {

    var methods = {
        _create: function( settings ) {
            var $this = $( this );
            $this.data( "locationpicker", settings );

            settings.firstOpen = true;

            $this.hide()
                .css( "position", "fixed" )
                .css( "left", "0" )
                .css( "top", "0" )
                .css( "width", "100%" )
                .css( "height", "100%" );

            var mapEl = $( "<div>" )
                .css( "width", "100%" ).css( "height", "100%" )
                .appendTo( $this );
            
            settings.map = new google.maps.Map(
                mapEl.get()[0],
                {
                    zoom: 6,
                    // center: center,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
            );

            settings.marker = new google.maps.Marker({
                map: settings.map,
                draggable: true
            });

            var menuEl = $( "<div>" );
            var selectBtn = $( "<button>Valitse</button>" )
                .addClass( "big proceed" ).appendTo( menuEl )
                .click(function() {
                    methods.select.call( $this );
                    return false;
                }
            );
            var cancelBtn = $( "<button>Peruuta</button>" )
                .addClass( "big cancel" ).appendTo( menuEl )
                .click(function() {
                    methods.cancel.call( $this );
                    return false;
                }
            );

            settings.map.controls[google.maps.ControlPosition.BOTTOM_CENTER]
                .push( menuEl.get()[0] );
                

        },

        open: function(currentPos, selectCallback) {
            var $this = $( this );
            var settings = $this.data( "locationpicker" );
            settings.selectCallback = selectCallback;
            $this.show();

            if ( settings.firstOpen ) {
                // Initial map size is calculated wrong because div is hidden
                google.maps.event.trigger( settings.map, "resize" );
                settings.firstOpen = false;
            }

            // Only accept valid strings
            if ( currentPos && currentPos.match(/^-?\d*\.\d*,-?\d*.\d*$/) ) {
                var coords = currentPos.split(',', 2);
                var latLng = new google.maps.LatLng(coords[0], coords[1]);
            } else {
                // Use default position
                var latLng = new google.maps.LatLng(
                    settings.center.lat,
                    settings.center.lng
                );
                
            }
            settings.map.setCenter( latLng );
            settings.marker.setPosition( latLng );
        },

        cancel :function() {
            this.hide();
        },

        select: function() {
            var settings = this.data( "locationpicker" );
            var latLng = settings.marker.getPosition();
            var pos = latLng.lat() + "," + latLng.lng();
            if ( $.isFunction(settings.selectCallback) ) {
                settings.selectCallback.call( this, pos );
            }
            this.hide();
        }
    }
    
    $.fn.locationpicker = function( method ) {

        var settings = {
            center: {
                lat: "64.94216",
                lng: "26.235352"
            }
        };
        var args = Array.prototype.slice.call( arguments, 1 );
        return this.each(function() {
            if ( methods[method] ) {
                return methods[ method ]
                    .apply( this, args );

            } else if ( typeof method === "object" || !method ) {
                var options = {};
                if ( typeof method === "object" ) {
                    $.extend( true, options, settings, method ); // deep
                } else {
                    options = settings;
                }
                return methods._create.call( this, options );

            } else {
                $.error( 
                    "Method " +  method + " does not exist on jQuery.ajaxform" 
                );
            }  
        });


    }

})( jQuery );
