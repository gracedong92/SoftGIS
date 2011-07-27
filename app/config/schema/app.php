<?php

class AppSchema extends CakeSchema 
{

    var $name = 'App';

    var $polls = array(
        'id' => array(
            'type' => 'integer', 
            'null' => false, 
            'key' => 'primary'
        ),
        'name' => array(
            'type' => 'string',
            'null' => false,
            'length' => '255'
        ),
        'author_id' => array(
            'type' => 'integer',
            'null' => false,
        ),
        'public' => array(
            'type' => 'boolean',
            'null' => false,
            'default' => 0
        ),
        'published' => array(
            'type' => 'datetime',
            'null' => true,
            'default' => null
        ),
        'answers' => array(
            'type' => 'integer',
            'null' => false,
            'default' => 0
        ),
        'description' => array(
            'type' => 'text',
            'null' => true,
            'default' => ''
        )
    );

    var $questions = array(
        'id' => array(
            'type' => 'integer', 
            'null' => false, 
            'key' => 'primary'
        ),
        'poll_id' => array(
            'type' => 'integer', 
            'null' => false 
        ),
        'num' => array(
            'type' => 'integer',
            'null' => false
        ),
        'type' => array(
            'type' => 'integer',
            'null' => false
        ),
        'text' => array(
            'type' => 'text',
            'null' => false
        ),
        'low_text' => array(
            'type' => 'string',
            'null' => true,
            'default' => null
        ),
        'high_text' => array(
            'type' => 'string',
            'null' => true,
            'default' => null
        ),
        'lat' => array(
            'type' => 'float',
            'null' => true,
            'default' => null
        ),
        'lng' => array(
            'type' => 'float',
            'null' => true,
            'default' => null
        ),
        'answer_location' => array(
            'type' => 'boolean',
            'null' => false,
            'default' => 0
        ),
        'answer_visible' => array(
            'type' => 'boolean',
            'null' => false,
            'default' => 0
        ),
        'comments' => array(
            'type' => 'boolean',
            'null' => false,
            'default' => 0
        ),
    );

    var $answers = array(
        'id' => array(
            'type' => 'integer', 
            'null' => false, 
            'key' => 'primary'
        ),
        'hash' => array(
            'type' => 'string',
            'null' => false
        ),
        'question_id' => array(
            'type' => 'integer', 
            'null' => false 
        ),
        'answer' => array(
            'type' => 'text',
            'null' => false
        ),
        'lat' => array(
            'type' => 'float',
            'null' => true,
            'default' => null
        ),
        'lng' => array(
            'type' => 'float',
            'null' => true,
            'default' => null
        ),
    );

    var $authors = array(
        'id' => array(
            'type'=>'integer', 
            'null' => false, 
            'key' => 'primary'
        ),
        'username' => array(
            'type' => 'string',
            'null' => false,
            'length' => '50'
        ),
        'password' => array(
            'type' => 'string',
            'null' => '40',
        )
    );


    var $objects = array(
        'id' => array(
            'type'=>'integer', 
            'null' => false, 
            'key' => 'primary'
        ),
        'name' => array(
            'type' => 'string',
            'length' => '50',
            'null' => false
        ),
        'content' => array(
            'type' => 'text'
        ),
        'type' => array(
            'type' => 'string',
            'length' => '50',
            'null' => false
        ),
        'modifiers' => array(
            'type' => 'string',
            'length' => '50',
            'null' => true
        ),
        'latlngs' => array(
            'type' => 'text',
            'null' => false,
            'default' => null
        )
    );

}
