

<script>

$( document ).ready(function() {

    var questionLatLng = new google.maps.LatLng(
        "<?php echo $question['Question']['lat']; ?>",
        "<?php echo $question['Question']['lng']; ?>",
    );

    var map = new google.maps.Map(
        $( "#map" ).get()[0],
        {
            zoom: 6,
            center
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
    );
});

</script>


<div class="text">
    <div class="question">
        <?php echo $question['Question']['text']; ?>
    </div>
    <div class="answer">
        <?php echo $this->element(
            'answer', 
            array('question' => $question)
        ); ?>
    </div>
</div>


<div id="map">
</div>