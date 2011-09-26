var Map = Spine.Controller.create({
    proxied: ["setCenter", "setSelectable", "hide", "initMap", "getAnswerLoc"],
    init: function() {
    },
    initMap: function(pos, zoom) {
        this.el.show();
        this.map = new google.maps.Map(this.el.get()[0], {
            zoom: zoom ? zoom : 14,
            center: pos,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        this.marker = new google.maps.Marker({
            position: pos,
            map: null,
            draggable: true
        });

        var me = this;
        google.maps.event.addListener(this.map, "click", function(e) {
            if (me.selectable && !me.locSelected) {
                me.marker.setPosition(e.latLng);
                me.marker.setMap(this);
                me.locSelected = true;
            }
        });

        var map = this.map;
        if (this.markers) {
            _.each(this.markers, function(data) {
                var marker = new google.maps.Marker({
                    map: map,
                    title: data.name,
                    position: new google.maps.LatLng(
                        data.lat,
                        data.lng
                    ),
                    // Use icon if set
                    icon: data.icon ? markerIconPath + data.icon : null
                });
                var infoWindow = new google.maps.InfoWindow({
                    content: data.content
                });
                google.maps.event.addListener(marker, "click", function() {
                    infoWindow.open(map, marker);
                });
            }, this);
        }

        if (this.paths) {
            _.each(this.paths, function(data) {
                var infoWindow = new google.maps.InfoWindow({
                    content: data.content
                });
                var encodedPaths = data.coordinates.split( " " );
                for (var i in encodedPaths) {
                    var encodedPath = encodedPaths[i];
                    encodedPath = encodedPath.replace(/\\\\/g, "\\");

                    var decodedPath = google.maps.geometry.encoding.decodePath(
                        encodedPath
                    );

                    if (data.type == 1) {
                        var path = new google.maps.Polyline({
                            map: map,
                            strokeColor: data.stroke_color ? "#" + data.stroke_color : "#333",
                            strokeOpacity: data.stroke_opacity ? data.stroke_opacity : 1,
                            strokeWeight: data.stroke_weight ? data.stroke_weight : 1,
                            path: decodedPath
                        });
                    } else {
                        var path = new google.maps.Polygon({
                            map: map,
                            strokeColor: data.stroke_color ? "#" + data.stroke_color : "#333",
                            strokeOpacity: data.stroke_opacity ? data.stroke_opacity : 1,
                            strokeWeight: data.stroke_weight ? data.stroke_weight : 1,
                            fillColor: data.fill_color ? "#" + data.fill_color : "#333",
                            fillOpacity: data.fill_opacity ? data.fill_opacity : 0.5,
                            path: decodedPath
                        });
                    }
                }
            }, this);
        }
        
    },
    setCenter: function(lat, lng, zoom) {
        this.el.show();
        var pos = new google.maps.LatLng(lat, lng);
        zoom = parseInt(zoom, 10);
        if (!this.map) {
            this.initMap(pos, zoom);
        } else {
            this.map.setCenter(pos);
            if (zoom) {
                this.map.setZoom(zoom);
            }
        }

        if (this.marker) {
            this.marker.setMap(null);
        }
    },
    setSelectable: function(enabled) {
        this.locSelected = false;
        this.selectable = enabled;
    },
    getAnswerLoc: function() {
        if (this.marker && this.locSelected) {
            var loc = this.marker.getPosition();
            return loc.lat() + "," + loc.lng();
        } else {
            return "";
        }
    },
    hide: function() {
        this.el.hide();
    }
});