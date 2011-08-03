<?php

class PollsController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'author';
    }

    public function index()
    {
        $authorId = $this->Auth->user('id');
        $this->Poll->recursive = -1;
        $polls = $this->Poll->findAllByAuthorId($authorId);

        $this->set('polls', $polls);
    }


    public function edit($id = null)
    {
        $authorId = $this->Auth->user('id');

        if (!empty($id)) {
            $poll = $this->Poll->findById($id);

            // Poll not found or someone elses
            if (empty($poll) || $poll['Poll']['author_id'] != $authorId) {
                $this->cakeError('pollNotFound');
            }

            // Published poll shouldn't be edited anymore
            if (!empty($poll['Poll']['published'])) {
                $this->Session->setFlash('Julkaistua kyselyä ei voida enää muokata');
                $this->redirect(array('action' => 'index'));
            }
        }

        if (!empty($this->data)) {

            $this->data['Poll']['author_id'] = $authorId;

            // Parse Path IDs
            $this->data['Path'] = explode(',', $this->data['Poll']['paths']);
            unset($this->data['Poll']['paths']);

            if ($this->Poll->saveAll($this->data, array('validate'=>'first'))){

                $this->Session->setFlash('Kysely tallennettu');
                $this->redirect(array('action' => 'index'));

            } else {
                $this->Session->setFlash('Tallentaminen epäonnistui');

                // Order questions
                if (isset($this->data['Question'])) {
                    usort(
                        $this->data['Question'], 
                        function($a, $b) {
                            if ($a['num'] == $b['num']) {
                                return 0;
                            }
                            return ($a < $b) ? -1 : 1;
                        }
                    );
                }

                // Get path names
                $paths = $this->Poll->Path->find(
                    'list',
                    array(
                        'conditions' => array(
                            'Path.id' => $this->data['Path']
                        )
                    )
                );
                $this->data['Path'] = array();
                foreach ($paths as $id => $name) {
                    $this->data['Path'][] = array(
                        'id' => $id,
                        'name' => $name
                    );
                }
            }

        }

        if (empty($this->data) && !empty($poll)) {
            $this->data = $poll;
            $this->data['Path'] = array();
            foreach ($poll['Path'] as $p) {
                $this->data['Path'][] = array(
                    'id' => $p['id'],
                    'name' => $p['name']
                );
            }
        }
    }

    public function publish($pollId = null)
    {
        $authorId = $this->Auth->user('id');
        $this->Poll->id = $pollId;

        if (!$this->Poll->exists() 
            || $this->Poll->field('author_id') != $authorId) {
            $this->cakeErro('pollNotFound');
        }

        if ($this->Poll->field('published') == null) {
            $this->Poll->saveField('published', date('Y-m-d H:i:s'));
            $this->Session->setFlash('Kysely julkaistu.');
        } else {
            $this->Session->setFlash('Kysely on jo julkaistu');
        }

        $this->redirect(array('action' => 'index'));
    }
}