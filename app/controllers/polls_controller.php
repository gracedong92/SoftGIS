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

    public function modify($id = null)
    {
        $authorId = $this->Auth->user('id');

        if (!empty($id)) {
            $poll = $this->Poll->find(
                'first',
                array(
                    'conditions' => array(
                        'Poll.id' => $id
                    ),
                    'contain' => array(
                        'Question',
                        'Path' => array(
                            'id',
                            'name'
                        ),
                        'Marker' => array(
                            'id',
                            'name'
                        )
                    )
                )
            );
            // Poll not found or someone elses
            if (empty($poll) || $poll['Poll']['author_id'] != $authorId) {
                $this->cakeError('pollNotFound');
            }

            // Published poll shouldn't be edited anymore
            if (!empty($poll['Poll']['published'])) {
                $this->Session->setFlash('Julkaistua kyselyä ei voida enää muokata');
                $this->redirect(array('action' => 'index'));
            }

        } else {
            // Empty poll
            $poll = array(
                'Poll' => array(
                    'name' => null,
                    'public' => null,
                    'published' => null,
                    'welcome_text' => null,
                    'thanks_text' => null
                ),
                'Question' => array(),
                'Path' => array(),
                'Marker' => array()
            );
        }

        // Save
        if (!empty($this->data)) {
            $data = $this->_jsonToPollModel($this->data);
            // debug($data);die;
            if ($this->Poll->saveAll($data, array('validate'=>'first'))){
                $this->Session->setFlash('Kysely tallennettu');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Tallentaminen epäonnistui');
                $poll = $data;
                $errors = $this->Poll->validationErrors;
                foreach ($errors as $err) {
                    $this->Session->setFlash($err);
                }
                // debug($errors);die;
            }
        }



        $this->set('poll', $poll);
        // debug($poll);die;
    }

    public function _jsonToPollModel($data)
    {
        $json = json_decode($data, true);
        $data = array(
            'Poll' => $json['poll'],
            'Question' => array(),
            'Path' => array(),
            'Marker' => array()
        );
        $data['Poll']['author_id'] = $this->Auth->user('id');
        $data['Poll']['public'] = empty($data['Poll']['public']) ? 0 : 1;

        foreach ($json['questions'] as $q) {
            $q['answer_location'] = empty($q['answer_location']) ? 0 : 1;
            $q['answer_visible'] = empty($q['answer_visible']) ? 0 : 1;
            $q['comments'] = empty($q['comments']) ? 0 : 1;
            unset($q['visible']);
            $data['Question'][] = $q;
        }
        if (empty($data['Question'])) {
            unset($data['Question']);
        }

        foreach ($json['paths'] as $p) {
            $data['Path'][] = $p['id'];
        }
        if (empty($data['Path'])) {
            $data['Path'][] = null;
        }
        foreach ($json['markers'] as $m) {
            $data['Marker'][] = $m['id'];
        }
        if (empty($data['Marker'])) {
            $data['Marker'][] = null;
        }
        return $data;
    }

    public function _toViewModelArray($model)
    {
        // $
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
            debug($this->data);die;
            unset($this->data['Poll']['paths']);
            // debug($this->data);die;
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

        // debug($this->data);die;
    }

    public function publish($pollId = null)
    {
        $authorId = $this->Auth->user('id');
        $this->Poll->id = $pollId;

        if (!$this->Poll->exists() 
            || $this->Poll->field('author_id') != $authorId) {
            $this->cakeError('pollNotFound');
        }

        if ($this->Poll->field('published') == null) {
            $this->Poll->saveField('published', date('Y-m-d H:i:s'));
            $this->Session->setFlash('Kysely julkaistu.');
        } else {
            $this->Session->setFlash('Kysely on jo julkaistu');
        }

        $this->redirect(array('action' => 'index'));
    }


    /**
     * View poll hashes
     */
    public function hashes($pollId = null)
    {
        $authorId = $this->Auth->user('id');
        $this->Poll->id = $pollId;

        if (!$this->Poll->exists()
            || $this->Poll->field('author_id') != $authorId) {
            $this->cakeError('pollNotFound');
        }

        $hashes = $this->Poll->Hash->findAllByPollId($pollId);
        $this->set('hashes', $hashes);
        $this->set('pollId', $pollId);
    }


    /**
     * Generate new hashes
     */
    public function generatehashes($pollId = null)
    {
        $authorId = $this->Auth->user('id');
        $this->Poll->id = $pollId;

        if (!$this->Poll->exists()
            || $this->Poll->field('author_id') != $authorId) {
            $this->cakeError('pollNotFound');
        }
        
        $count = $this->data['count'];

        if (!is_numeric($count)) {
            $this->Session->setFlas('Virheellinen lukumäärä');
        } else {
            $this->Poll->generateHashes($count);
        }

        $this->redirect(array('action' => 'hashes', $pollId));
    }

    // protected function _toViewModel($data = array())
    // {
    //     $vm = array();
    //     if (isset($data['Poll'])) {
    //         $p = $data['Poll'];
    //         if (isset($p['id']))
    //             $vm['id'] = $p['id'];
    //         if (isset($p['name']))
    //             $vm['name'] = $p['name'];

    //     }
    // }
}








