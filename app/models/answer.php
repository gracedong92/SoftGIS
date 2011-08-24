<?php

class Answer extends AppModel
{
    public $actsAs = array(
        'LatLng'
    );

    public $belongsTo = array(
        'Question',
        'Response'
    );

    public function afterFind($results, $primary)
    {
        if (!$primary) {
            return $this->Behaviors->LatLng->afterFind($this, $results, false);
        }
    }
}