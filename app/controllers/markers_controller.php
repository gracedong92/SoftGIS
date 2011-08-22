<?php

class MarkersController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'author';
    }

    public function add()
    {
        if (!empty($this->data)) {
            if ($this->Marker->save($this->data)) {
                $this->Session->setFlash('Karttamerkki tallennettu');
                $this->redirect(
                    array('controller' => 'polls', 'action' => 'index')
                );
            }
        }
    }

    public function create()
    {
        $authorId = $this->Auth->user('id');

        if (!empty($this->data)) {
            if ($this->Object->save($this->data)) {
                $this->data = null;
                $this->Session->setFlash('Merkki luotu');
            } else {
                // debug($this->Entry->invalidFields());die;
            }
        }
    }

    public function search()
    {
        if (!empty($this->params['url']['q'])) {
            $q = $this->params['url']['q'];
            $markers = $this->Marker->find(
                'list',
                array(
                    'conditions' => array(
                        'Marker.name LIKE' => '%' . $q . '%'
                    )
                )
            );
        } else {
            $markers = array();
        }

        $this->set('markers', $markers);
    }
}