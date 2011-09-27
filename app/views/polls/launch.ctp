<script>

$(document).ready(function() {
    $("#PollLaunch").datepicker();
    $("#PollEnd").datepicker();
});

</script>


<h2>Mistä mihin kysely on käyttäjien vastattavissa</h2>

<?php echo $this->Form->create('Poll'); ?>

<div class="input text">
    <label>Alkamispäivä</label>
    <?php echo $this->Form->text(
        'launch',
        array(
            'type' => 'text', 
            'class' => 'small',
        )
    ); ?>
</div>

<div class="input text">
    <label>Päättymispäivä</label>
    <?php echo $this->Form->text(
        'end',
        array(
            'type' => 'text', 
            'class' => 'small',
        )
    ); ?>
</div>

<button type="submit" id="saveButton">
    Tallenna muutokset
</button>
<?php 
if (!empty($poll['Poll']['id'])) {
    $url = array('action' => 'view', $poll['Poll']['id']);
} else {
    $url = array('action' => 'index');
}
echo $this->Html->link(
    'Peruuta',
    $url,
    array(
        'class' => 'button cancel small'
    )
); 
?>
<?php echo $this->Form->end(); ?>