<h2><?php echo $poll['name']; ?></h2>

<?php echo $this->Html->link(
    'Muokkaa kyselyä',
    array(
        'action' => 'modify',
        $poll['id']
    ),
    array(
        'class' => 'button'
    )
); ?>

<?php echo $this->Html->link(
    'Testaa kyselyä',
    array(
        'controller' => 'answers',
        'action' => 'test',
        $poll['id']
    ),
    array(
        'class' => 'button'
    )
); ?>

<?php echo $this->Html->link(
    'Vastaukset',
    array(
        'action' => 'answers',
        $poll['id']
    ),
    array(
        'class' => 'button'
    )
); ?>

<br/><br/>
<h3>Tiedot</h3>
<dl class="details">
    <dt>Tervetuloateksti</dt>
    <dd><?php echo $poll['welcome_text']; ?></dd>

    <dt>Kiitosteksi</dt>
    <dd><?php echo $poll['thanks_text']; ?></dd>

    <dt>Kaikille avoin</dt>
    <dd><?php echo $poll['public'] ? 'Kyllä' : 'Ei'; ?></dd>
</dl>

<h3>Reitit</h3>
<dl class="details">
    <?php foreach ($paths as $path): ?>
        <dt>
            <?php echo $path['name']; ?>
        </dt>
        <dd>
            <?php echo $path['content']; ?>
        </dd>
    <?php endforeach; ?>
</dl>

<h3>Merkit</h3>
<dl class="details">
    <?php foreach ($markers as $marker): ?>
        <dt>
            <?php echo $marker['name']; ?>
        </dt>
        <dd>
            <?php echo $marker['content']; ?>
        </dd>
    <?php endforeach; ?>
</dl>

<h3>Kysymykset</h3>
<ol style="margin-left: 2em;">
<?php foreach ($questions as $q): ?>
    <li>
        <h4><?php echo $q['text']; ?></h4>
        <dl class="details">
            <dt>Vastaus</dt>
            <dd><?php echo $answers[$q['type']]; ?></dd>

            <dt>Sijainti</dt>
            <dd> 
                <?php echo empty($q['latlng']) ? 'Ei' : $q['latlng']; ?>
            </dd>

            <dt>Zoom-taso</dt>
            <dd> 
                <?php echo empty($q['zoom']) ? 'Ei' : $q['zoom']; ?>
            </dd>

            <dt>Sijainti vastaajalta</dt>
            <dd> 
                <?php echo $q['answer_location'] ? 'Kyllä' : 'Ei'; ?>
            </dd>

            <dt>Yleiset vastaukset</dt>
            <dd> 
                <?php echo $q['answer_visible'] ? 'Kyllä' : 'Ei'; ?>
            </dd>

            <dt>Vastausten kommentointi</dt>
            <dd> 
                <?php echo $q['comments'] ? 'Kyllä' : 'Ei'; ?>
            </dd>
        </dl>
    </li>
<?php endforeach; ?>
</ol>

