<script>

var map;
var questionMarker;

function createMarker(data) {
    var marker = new google.maps.Marker({
        map: map,
        title: data.name,
        position: new google.maps.LatLng(
            data.lat,
            data.lng
        )
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

<?php if ($question['Question']['lat']): ?>
    // Question position
    var questionLatLng = new google.maps.LatLng(
        "<?php echo $question['Question']['lat']; ?>",
        "<?php echo $question['Question']['lng']; ?>"
    );

    map = new google.maps.Map(
        $( "#map" ).get()[0],
        {
            zoom: 12,
            center: questionLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
    );
    questionMarker = new google.maps.Marker({
        map: map,
        position: questionLatLng
    });

    <?php /* Create Markers */ ?>
    <?php foreach ($question['Poll']['Marker'] as $marker): ?>
        createMarker(<?php echo json_encode($marker); ?>);
    <?php endforeach; ?>

    <?php /* Create Paths */ ?>
    <?php foreach ($question['Poll']['Path'] as $path): ?>
        createPath(<?php echo json_encode($path); ?>);
    <?php endforeach; ?>


    <?php if ($question['Question']['answer_location']): ?>
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
<?php if ($question['Question']['type'] == 1): ?>
    var answerSelector = "textarea";
<?php else: ?>
    var answerSelector = "input:checked";
<?php endif; ?>


    // Validate answer before submit
    $( "#answer-form" ).submit(function() {

<?php if ($question['Question']['answer_location']): ?>
        // Make sure user has selected location
        var lat = $( "#lat" ).val();
        var lng = $( "#lng" ).val();

        if ( !lat || !lng ) {
            $.meow({
                icon: "/css/images/nyan-cat.gif",
                message: "Et ole merkinnyt sijaintia kartalta"
            });
            return false;
        }
        
<?php endif; ?>

        // Make sure user has answered something
        var val = $( this ).find( answerSelector ).val();
        if ( !val ) {
            $.meow({
                icon: "/css/images/nyan-cat.gif",
                message: "Et ole vastannut kysymykseen mitään"
            });
            return false;
        }

        // All valid, submit form
        return true;
    });

});

</script>

<div class="answer">
    <h3><?php echo $question['Question']['text']; ?></h3>
    <form method="POST" id="answer-form">
        <div class="input">
            <?php echo $this->element(
                'answer', 
                array('question' => $question)
            ); ?>
        </div>


<?php /* Answer requires location */ ?>
<?php if ($question['Question']['answer_location']): ?>
            <input type="hidden" value="" id="lat" 
                name="data[Answer][lat]" />
            <input type="hidden" value="" id="lng" 
                name="data[Answer][lng]" />
<?php endif; ?>


        <button type="submit">Seuraava kysymys</button>
    </form>

<?php /* Question has position */ ?>
<?php if ($question['Question']['lat']): ?>
    <div id="map" class="map"></div>
<?php endif; ?>

</div>
