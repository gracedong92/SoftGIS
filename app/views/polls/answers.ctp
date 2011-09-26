<?php echo $this->Html->link(
    'Muokkaa',
    array(
        'action' => 'modify',
        $pollId
    ),
    array(
        'class' => 'button'
    )
); ?>

<?php echo $this->Html->link(
    'Kokeile',
    array(
        'controller' => 'answers',
        'action' => 'test',
        $pollId
    ),
    array(
        'class' => 'button'
    )
); ?>

<?php echo $this->Html->link(
    'Julkaise',
    array(
        'action' => 'publish',
        $pollId
    ),
    array(
        'id' => 'publish',
        'class' => 'button'
    )
); ?>

<?php echo $this->Html->link(
    'Vastaukset',
    array(
        'action' => 'answers',
        $pollId
    ),
    array(
        'class' => 'button'
    )
); ?>

<div class="input textarea">
    <label>Vastaukset</label>
    <textarea rows="20">
<?php foreach ($answers as $a): ?>
<?php echo $a; ?>

<?php endforeach; ?>
    </textarea>
</div>