<script>

var markerIconPath = "<?php echo $this->Html->url('/markericons/'); ?>";


$( document ).ready(function() {

    var defaultPos = new google.maps.LatLng( "64.94216", "26.235352" );

    var map = new google.maps.Map(
        $( "#map" ).get()[0],
        {
            disableDoubleClickZoom: true,
            zoom: 3,
            center: defaultPos,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
    );

    var marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: defaultPos
    });

    $( "#MarkerAddForm" ).submit(function() {
        var latLng = marker.getPosition();

        $( "#MarkerLatlng" ).val( latLng.lat() + "," + latLng.lng() );
        
        // return false;
    });

    $( "#MarkerIcon" ).change(function() {
        var icon = $( this ).val();
        if ( icon == "default" ) {
            marker.setIcon( null );
        } else {
            marker.setIcon( markerIconPath + icon );
        }
    });

});

</script>

<h1>Luo uusi karttamerkki</h1>

<?php echo $this->Form->create('Marker'); ?>
<?php echo $this->Form->input('name', array('label' => 'Nimi')); ?>
<?php echo $this->Form->input('content', array('label' => 'Sisältö')); ?>
<?php echo $this->Form->input('icon', array('label' => 'Kuvake')); ?>
<?php echo $this->Form->input('latlng', array('type' => 'hidden')); ?>
<div class="input map-container">
    <label>Sijainti</label>
    <div id="map" class="map">
    </div>
</div>
<button type="submit">Luo karttamerkki</button>
<?php echo $this->Form->end(); ?>