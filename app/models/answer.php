<?php

class Answer extends AppModel
{
    public $actsAs = array(
        'LatLng'
    );

    public $belongsTo = array(
        'Question'
    );

    public function afterFind($results, $primary)
    {
        if (!$primary) {
            return $this->Behaviors->LatLng->afterFind($this, $results, false);
        }
    }

    public function validHash($pollId, $hash)
    {
        App::import('Model', 'Poll');
        $poll = new Poll();
        return true;
    }

    public function generateHash($count = 1)
    {
        $hashs = array();
        for ($i=0;$i<10;$i++) {
            $hashs[] = md5(uniqid(rand(), true));
        }
        if ($count == 1) {
            return $hashs[0];
        } else {
            return $hashs;
        }
    }
}