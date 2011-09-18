<?php echo $this->Html->script('answer'); ?>

<script>
$( document ).ready(function() {
    var question = <?php echo json_encode($question); ?>;
    var markers = <?php echo json_encode($markers); ?>;
    var paths = <?php echo json_encode($paths); ?>;
    
    initQuestion(question);
    initMarkers(markers);
    initPaths(paths);

    <?php if (!empty($answers)) {
        echo 'initAnswers(' . json_encode($answers) . ');';
    } ?>

    $( "#toggleHelp" ).click(function() {
        $( ".help" ).fadeToggle();
        return false;
    });
});
</script>

<div class="answerMenu">
    <?php echo $this->Html->link(
        'Apua',
        '#help',
        array('class' => 'button', 'id' => 'toggleHelp')
    ); ?>
</div>

<div class="help">
    <p>Tähän ohjeet vastaamiseen</p>
    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
</div>

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

        <div class="answerNav">
            <button type="submit">Seuraava kysymys</button>
        </div>
    </form>

<?php /* Question has position */ ?>
<?php if ($question['lat']): ?>
    <div id="map" class="map"></div>
<?php endif; ?>

</div>
