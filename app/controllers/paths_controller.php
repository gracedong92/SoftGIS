<?php

class PathsController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'author';
    }


    public function add()
    {
        $authorId = $this->Auth->user('id');

        if (!empty($this->data)) {
            if ($this->Path->save($this->data)) {
                $this->data = null;
                $this->Session->setFlash('Reitti tallennettu');
            }
        }
    }

    public function search()
    {
        if (!empty($this->params['url']['q'])) {
            $q = $this->params['url']['q'];
            $paths = $this->Path->find(
                'list',
                array(
                    'conditions' => array(
                        'Path.name LIKE' => '%' . $q . '%'
                    )
                )
            );
        } else {
            $paths = array();
        }

        $this->set('paths', $paths);
    }
}