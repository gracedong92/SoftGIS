<script>

function gisToArray(gis) {
   var lines = gis.split( /\n/ );
   
   var paths = new google.maps.MVCArray();

   // var firstCoord;
   var currentPath = null;
   for ( var i in lines ) {

       var line = lines[i];
        if ( line.match(/PLINE \d*/) ) {
            if ( currentPath ) {
                paths.push(currentPath);
            } 
            currentPath = new google.maps.MVCArray();

        } else if ( line.match(/^\d*.\d* \d*.\d*$/) ) {
            var coords = line.split( " " );
            if ( coords.length == 2 ) {
                var latLng = new google.maps.LatLng(coords[1], coords[0]);
                currentPath.push(latLng);
            }
        }
    }

    // Add last path
    if ( currentPath ) {
        paths.push(currentPath);
    }
    return paths;
}


$( document ).ready(function() {
    $( "#PathAddForm" ).submit(function() {
        var paths = gisToArray( $("#PathPolyline").val() );
        var encodedPaths = [];
        paths.forEach(function(path) {
            encodedPaths.push( google.maps.geometry.encoding.encodePath(path) );
        });
        $( "#PathPolyline" ).val( encodedPaths.join(" ") );
        return true;
    });
});

</script>


<h1>Luo uusi reitti</h1>

<?php echo $this->Form->create('Path'); ?>
<?php echo $this->Form->input('name', array('label' => 'Nimi')); ?>
<?php echo $this->Form->input('content', array('label' => 'Sisältö')); ?>
<?php echo $this->Form->input('color', array('label' => 'Väri')); ?>
<?php echo $this->Form->input('polyline', array('label' => 'Reittidata')); ?>
<?php echo $this->Form->end('Luo'); ?>