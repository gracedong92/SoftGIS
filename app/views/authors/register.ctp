<?php
    echo $this->Session->flash('auth');
    echo $this->Form->create('Author');
    echo $this->Form->input('username');
    echo $this->Form->input('password');
    echo $this->Form->end('Register');
?>
