<?php

class Poll extends AppModel
{
    public $hasMany = array(
        'Question' => array(
            'foreign_key' => 'poll_id',
            'order' => 'Question.num ASC'
        )
    );

    public $hasAndBelongsToMany = array(
        'Path' => array(
            'joinTable' => 'polls_paths'
        )
    );

    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty',
            'message' => 'Anna kyselylle nimi'
        )
    );
}