<script>

var markerIconPath = "<?php echo $this->Html->url('/markericons/'); ?>";

var map;
var questionMarker;
var questionLocation;
var questionZoom;

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
            map: map,
            strokeColor: "#333",
            path: decodedPath
        });
    }
}

$( document ).ready(function() {

<?php if ($question['lat']): // Question has position ?>
    questionZoom = parseInt(<?php echo $question['zoom']; ?>);
    questionLatLng = new google.maps.LatLng(
        "<?php echo $question['lat']; ?>",
        "<?php echo $question['lng']; ?>"
    );

    map = new google.maps.Map( $( "#map" ).get()[0], {
            zoom: questionZoom,
            center: questionLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
    );
    questionMarker = new google.maps.Marker({
        map: map,
        position: questionLatLng,
        icon: "/img/question_icon.png"
    });

<?php foreach ($poll['Marker'] as $marker): // Create markers ?>
        createMarker(<?php echo json_encode($marker); ?>);
<?php endforeach; ?>

<?php foreach ($poll['Path'] as $path): // Create paths ?>
        createPath(<?php echo json_encode($path); ?>);
<?php endforeach; ?>

<?php if ($question['answer_location']): // Answer reqs location ?>
    // Create answer marker on first map click
    var answerMarker;
    google.maps.event.addListenerOnce(map, "click", function(e) {
        answerMarker = new google.maps.Marker({
            map: map,
            position: e.latLng,
            draggable: true
        });

        // Updates answer location immediately when marker is dropped
        google.maps.event.addListener(answerMarker, "dragend", function(e) {
            $( "#lat" ).val( e.latLng.lat() );
            $( "#lng" ).val( e.latLng.lng() );
        });

        // Store initial position
        google.maps.event.trigger( answerMarker, "dragend", e );
    });
<?php endif; // Question.answer_location ?>
<?php endif; // Question.lat ?>


<?php /* Input jQ-selector depends on Question.type */ ?>
<?php if ($question['type'] == 1): ?>
    var answerSelector = "textarea";
<?php else: ?>
    var answerSelector = "input:checked";
<?php endif; ?>


    // Validate answer before submit
    $( "#answer-form" ).submit(function() {
        var continueSubmit = true;

<?php if ($question['lat'] && $question['answer_location']): // Req location ?>

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
                
<?php endif; ?>

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

});

</script>

<div class="answer">
    <h3><?php echo $question['text']; ?></h3>
    <form method="POST" id="answer-form">
        <div class="input">
            <?php echo $this->element(
                'answer', 
                array('question' => $question)
            ); ?>
        </div>


<?php /* Answer requires location */ ?>
<?php if ($question['answer_location']): ?>
            <input type="hidden" value="" id="lat" 
                name="data[Answer][lat]" />
            <input type="hidden" value="" id="lng" 
                name="data[Answer][lng]" />
<?php endif; ?>


        <button type="submit">Seuraava kysymys</button>
    </form>

<?php /* Question has position */ ?>
<?php if ($question['lat']): ?>
    <div id="map" class="map"></div>
<?php endif; ?>

</div>
