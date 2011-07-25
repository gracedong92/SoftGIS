<?php
    echo $this->Session->flash('auth');
    echo $this->Form->create('Author');
    echo $this->Form->input(
        'username', 
        array('label' => 'Käyttäjänimi')
    );
    echo $this->Form->input(
        'password',
        array('label' => 'Salasana')
    );
    echo $this->Form->end('Kirjaudu');
?>
