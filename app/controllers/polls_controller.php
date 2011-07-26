<?php

class PollsController extends AppController
{
    public function answer($id = null, $hash = null)
    {
        if ($id != null) {

            $this->Poll->id = $id;
            if ($this->Poll->exists()) {

                $this->Session->write(
                    'answer', 
                    array('poll' => $id)
                );

                if (!$this->Poll->field('public')) {
                    $this->Session->write('answer.hash', $hash);
                }

                $this->redirect(
                    array(
                        'controller' => 'answers', 
                        'action' => 'answer'
                    )
                );
            }

        }
    }


    public function edit($id = null)
    {
        $authorId = $this->Auth->user('id');

        if (!empty($this->data)) {
            $this->data['Poll']['author_id'] = $authorId;
            // debug($this->data);die;
            if ($this->Poll->saveAll(
                    $this->data, 
                    array('validate' => 'first')
                )
            ){
                $this->Session->setFlash('Kysely luotu');
                $this->redirect(
                    array(
                        'controller' => 'authors',
                        'action' => 'index'
                    )
                );
                return;
            } else {
                $this->Session->setFlash('Tallentaminen epäonnistui');
                // debug($this->data);die;
            }

        } else if (!empty($id)) {
            $this->data = $this->Poll->find(
                'first',
                array(
                    'conditions' => array(
                        'Poll.id' => $id
                    )
                )
            );
        }
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
        // debug($this->data['Question']);die;
    }
}