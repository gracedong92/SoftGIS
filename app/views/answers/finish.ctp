<h3>
    <?php echo $poll['Poll']['name']; ?>
</h3>
<div class="thanksText">
    <?php echo $poll['Poll']['thanks_text']; ?>
</div>

<?php if ($test): ?>
    <br/><br/><br/><br/>
    <h3>Huom. Testivastaus, vastauksia ei tallennettu</h3>
    <a href="<?php echo $this->Html->url(
            array(
                'controller' => 'polls',
                'action' => 'index'
            )
        ); ?>" class="button">
        Takaisin omiin kyselyihin
    </a>
<?php endif; ?>
