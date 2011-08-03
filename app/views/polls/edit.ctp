<?php echo $this->Html->script('locationpicker'); ?>
<?php echo $this->Html->script('create_poll'); ?>

<script>

<?php if (!empty($this->data['Path'])): ?>
    var paths = <?php echo json_encode($this->data['Path']); ?>
<?php else: ?>
    var paths = null;
<?php endif; ?>

$( document ).ready(function() {
    $( "#PollPaths" ).tokenInput("/paths/search.json", {
        prePopulate: paths
    });
});

</script>


<h1>Kysely</h1>
<!-- Form -->
<?php echo $this->Form->create('Poll'); ?>

<?php echo $this->Form->input(
    'id'
); ?>

<?php echo $this->Form->input(
    'name',
    array('label' => 'Nimi')
); ?>
<?php echo $this->Form->input(
    'description',
    array('label' => 'Kuvaus')
); ?>

<?php echo $this->Form->input(
    'public',
    array('label' => 'Kaikille avoin')
); ?>

<?php echo $this->Form->input(
    'paths',
    array('label' => 'Reitit')
); ?>

<div class="input">
    <label>Kysymykset</label>
    <ul id="questions">
    <?php 
        $index = 0;
        if (isset($this->data['Question'])) {
            foreach ($this->data['Question'] as $q){
               echo $this->element(
                    'question_edit', 
                    array_merge($q, array('i' => $index))
                );
                $index++;
            }
        }
    ?>
    </ul>
    <button type="button" id="create-question">Luo uusi kysymys</button>
</div>
<?php echo $this->Form->end('Tallenna'); ?>

<script>

var questionIndex = <?php echo $index; ?>

</script>


<div id="loc-picker">
</div>


<!-- Question Template -->
<script type="text/x-jquery-tmpl" id="question-tmpl">

<?php echo $this->element(
    'question_edit',
    array('template' => true)
); ?>

</script>
