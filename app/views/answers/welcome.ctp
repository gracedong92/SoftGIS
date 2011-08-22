<h3>
    <?php echo $poll['Poll']['name']; ?>
</h3>
<div class="welcomeText">
    <?php echo $poll['Poll']['welcome_text']; ?>
</div>
<a href="<?php echo $this->Html->url(array('action'=>'answer')); ?>">
<button>
    Aloita kysely
</button>
</a>