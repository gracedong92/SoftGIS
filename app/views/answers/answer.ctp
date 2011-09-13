<?php echo $this->Html->script('answer'); ?>

<script>
$( document ).ready(function() {
    var question = <?php echo json_encode($question); ?>;
    initQuestion(question);

    <?php if (!empty($answers)) {
        echo 'initAnswers(' . json_encode($answers) . ');';
    } ?>
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
