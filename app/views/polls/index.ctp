<script>

var confirmPublish = "Haluatko varmasti julkaista kyselyn? Julkaisun jälkeen kyselyä ei voida enää muokata";

$( document ).ready(function() {
    $( "a.publish" ).click(function() {
        var href = this.href;
        smoke.confirm( confirmPublish, function(e) {
            if ( e ) {
                window.location = href;
            }
        });
        return false;
    });
});

</script>

<h1>Omat kyselyt</h1>
<table class="list">
    <thead>
        <tr>
            <th>Nimi</th>
            <th>Julkaistu</th>
            <th>Julkinen</th>
            <th>Vastauksia</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($polls as $poll): ?>
            <tr>
                <td>
                    <?php echo $this->Html->link(
                        $poll['Poll']['name'],
                        array(
                            'controller' => 'polls', 
                            'action' => 'modify',
                            $poll['Poll']['id']
                        )
                    ); ?>
                </td>
                <td>
                    <?php if (empty($poll['Poll']['published'])) {
                        echo $this->Html->link(
                            'Julkaise', 
                            array('action' => 'publish', $poll['Poll']['id']),
                            array('class' => 'publish')
                        );
                    } else {
                        echo date(
                            'd.m.Y H:i:s', 
                            strtotime($poll['Poll']['published'])
                        );
                    }; ?>
                </td>
                <td>
                    <?php if ($poll['Poll']['public']) {
                        echo 'Kyllä';
                    } else {
                        echo 'Ei, ';
                        echo $this->Html->link(
                            'hashit',
                            array('action' => 'hashes', $poll['Poll']['id'])
                        ); 
                    } ?>
                </td>
                <td><?php echo $poll['Poll']['answers']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>