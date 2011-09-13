var map, questionMarker, questionLocation, questionZoom, locRequired = false;

function initQuestion(question) {

    if ( question.lat && question.lng ) {
        // Question has pos
        questionLocation = new google.maps.LatLng(
            question.lat,
            question.lng
        );

        map = new google.maps.Map( $( "#map" ).get()[0], {
                zoom: question.zoom ? parseInt(question.zoom, 10) : 14,
                center: questionLocation,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
        );

        questionMarker = new google.maps.Marker({
            map: map,
            position: questionLocation,
            icon: "/img/question_icon.png"
        });

        if ( question.marker ) {
            _.each( question.marker, function(marker) {
                createMarker(marker);
            });
        }

        if ( question.path ) {
            _.each( question.path, function(path) {
                createPath(path);
            });
        }

        if ( question.answer_location ) {
            locRequired = true;

            var answerMarker;
            google.maps.event.addListenerOnce(map, "click", function(e) {
                answerMarker = new google.maps.Marker({
                    map: map,
                    position: e.latLng,
                    draggable: true
                });

                // Updates answer location immediately when marker is dropped
                google.maps.event.addListener(
                    answerMarker, 
                    "dragend", 
                    function(e) {
                        $( "#lat" ).val( e.latLng.lat() );
                        $( "#lng" ).val( e.latLng.lng() );
                    }
                );

                // Store initial position
                google.maps.event.trigger( answerMarker, "dragend", e );
            });
        }
    }

    var answerSelector;
    if ( question.type == 1 ) {
        answerSelector = "textarea";
    } else {
        answerSelector = "input:checked";
    }

    // Validate answer before submit
    $( "#answer-form" ).submit(function() {
        var continueSubmit = true;

        if ( locRequired ) {
            // Make sure user has selected location
            var lat = $( "#lat" ).val();
            var lng = $( "#lng" ).val();

            if ( !lat || !lng ) {
                $( "#map" ).qtip({
                    content: "Et ole valinnut sijaintia kartalta",
                    position: {
                        my: "bottom center",
                        at: "top center",
                        adjust: {
                            x: 200
                        }
                    },
                    show: {
                        ready: true,
                        event: null
                    },
                    hide: {
                        event: null
                    },
                    style: {
                        classes: "ui-tooltip-shadow ui-tooltip-rounded ui-tooltip-red"
                    }
                })
                continueSubmit = false;
            } else {
                $( "#map" ).qtip( "destroy" );
            }
        }

        // Make sure user has answered something
        var val = $( this ).find( answerSelector ).val();
        if ( !val ) {
            $( answerSelector ).focus();
            $( "#answerField" ).qtip({
                content: "Et ole vastannut kysymykseen",
                position: {
                    my: "top center",
                    at: "bottom center",
                    adjust: {
                        x: -200
                    }
                },
                show: {
                    ready: true,
                    event: "focus"
                },
                hide: {
                    event: null
                },
                style: {
                    classes: "ui-tooltip-shadow ui-tooltip-rounded ui-tooltip-red"
                }
            });
            continueSubmit = false;
        } else {
            $( "#answerField" ).qtip( "destroy" );
        }

        return continueSubmit;
    });

}

function initAnswers(answers) {
    if ( !map ) {
        return;
    }

    _.each( answers, function(answer) {
        answer = answer.Answer;
        if ( answer.lat && answer.lng ) {
            createMarker({
                name: answer.answer,
                content: answer.answer,
                lat: answer.lat,
                lng: answer.lng
            });
        }
    });
}

function createMarker(data) {
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
}

function createPath(data) {
    var infoWindow = new google.maps.InfoWindow({
        content: data.content
    });
    var encodedPaths = data.polyline.split( " " );
    for (var i in encodedPaths) {
        var decodedPath = google.maps.geometry.encoding.decodePath(
            encodedPaths[i]
        );
        var path = new google.maps.Polyline({
        // var path = new google.maps.Polygon({
            map: map,
            strokeColor: "#333",
            path: decodedPath
        });
    }
}

