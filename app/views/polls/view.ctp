<script>

// var confirmPublish = "Haluatko varmasti julkaista kyselyn? Julkaisun jälkeen kyselyä ei voida enää muokata";

// $( document ).ready(function() {
//     $( "#publish" ).click(function() {
//         return confirm( confirmPublish );
//     });
// });
</script>

<div class="subnav">
    <?php
    echo $this->Html->link(
        'Muokkaa',
        array(
            'action' => 'modify',
            $poll['id']
        ),
        array(
            'class' => 'button',
            'title' => 'Muokkaa kyselyä'
        )
    );
    echo $this->Html->link(
        'Kokeile',
        array(
            'controller' => 'answers',
            'action' => 'test',
            $poll['id']
        ),
        array(
            'class' => 'button',
            'title' => 'Voit kokeilla kyselyyn vastaamista ennen sen julkaisua. Kokeiluvastauksia ei tallenneta.'
        )
    );
    echo $this->Html->link(
        'Aukioloaika',
        array(
            'action' => 'launch',
            $poll['id']
        ),
        array(
            'class' => 'button',
            'title' => 'Määrittele mistä mihin kysely on vastattavissa.'
        )
    );
    if ($poll['public'] == 0) {
        echo $this->Html->link(
            'Varmenteet',
            array(
                'action' => 'hashes',
                $poll['id']
            ),
            array(
                'class' => 'button',
                'title' => 'Luo ja tarkastele varmenteita, joiden avulla kyselyyn vastaajat todennetaan.'
            )
        );
    };
    echo $this->Html->link(
        'Vastaukset',
        array(
            'action' => 'answers',
            $poll['id']
        ),
        array(
            'class' => 'button',
            'title' => 'Tarkastele kyselyn vastauksia'
        )
    );
    ?>
</div>

<h2>Perustiedot</h2>
<table class="details">
    <tr>
        <th>Nimi</th>
        <td><?php echo $poll['name']; ?></td>
    </tr>
    <tr>
        <th>Vastattavissa</th>
        <td><?php echo $poll['launch'] . ' - ' . $poll['end']; ?></td>
    </tr>
    <tr>
        <th>Kuvaus</th>
        <td><?php echo $poll['welcome_text']; ?></td>
    </tr>
    <tr>
        <th>Kiitosteksti</th>
        <td><?php echo $poll['thanks_text']; ?></td>
    </tr>
    <tr>
        <th>Kaikille avoin</th>
        <td><?php echo $poll['public'] ? 'Kyllä' : 'Ei'; ?></td>
    </tr>
    <tr>
        <th>Reitit/Alueet</th>
        <td>
            <ul>
                <?php foreach ($paths as $path): ?>
                    <li><?php echo $path['name']; ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <th>Karttamerkit</th>
        <td>
            <ul>
                <?php foreach ($markers as $marker): ?>
                    <li><?php echo $marker['name']; ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
</table>

<h2>Kysymykset</h2>

<table class="details">
    <?php foreach ($questions as $q): ?>
        <tr>
            <td colspan="2"><h3><?php echo $q['num']; ?></h3></td>
        </tr>
        <tr>
            <th>Kysymys</th>
            <td><?php echo $q['text']; ?></td>
        </tr>
        <tr>
            <th>Vastaus</th>
            <td><?php echo $answers[$q['type']]; ?></td>
        </tr>
        <tr>
            <th>Sijainti</th>
            <td> 
                <?php echo empty($q['latlng']) ? 'Ei' : $q['latlng']; ?>
            </td>
        </td>
        <tr>
            <th>Zoom-taso</th>
            <td> 
                <?php echo empty($q['zoom']) ? 'Ei' : $q['zoom']; ?>
            </td>
        </tr>
        <tr>
            <th>Kohteen merkitseminen kartalle</th>
            <td> 
                <?php echo $q['answer_location'] ? 'Kyllä' : 'Ei'; ?>
            </td>
        </tr>
        <tr>
            <th>Vastaukset näkyvissä muille vastaajille</th>
            <td> 
                <?php echo $q['answer_visible'] ? 'Kyllä' : 'Ei'; ?>
            </td>
        </tr>
        <tr>
            <th>Vastausten kommentointi</th>
            <td> 
                <?php echo $q['comments'] ? 'Kyllä' : 'Ei'; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>


