<?php

class Author extends AppModel
{
    public $hasMany = array(
        'Poll' => array(
            'foreign_key' => 'author_id'
        )
    );
}