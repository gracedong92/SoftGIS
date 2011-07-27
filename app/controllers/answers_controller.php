<?php

class AnswersController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        error_reporting(E_ALL);
        $this->Auth->allow('*');
    }

    public function index($pollId = null, $hash = null)
    {
        $this->redirect(array('action' => 'poll', $pollId, $hash));
    }

    public function poll($pollId = null, $hash = null)
    {
        $this->loadModel('Poll');
    
        if ($pollId != null) {
            $this->Poll->id = $pollId;
            if ($this->Poll->exists()) {

                if (!$this->Poll->field('public')) {
                    // Validate hash if poll is not public
                    if (!$this->Answer->validHash($pollId, $hash)) {
                        $this->cakeError('invalidHash');
                    }
                } else {
                    //generate custom hash if public
                    $hash = $this->Answer->generateHash();
                    
                }

                $this->Session->write(
                    'answer', 
                    array(
                        'poll' => $pollId,
                        'hash' => $hash
                    )
                );

                $this->redirect(
                    array(
                        'action' => 'answer'
                    )
                );
            }
        }
        $this->cakeError('pollNotFound');
    }


    public function answer()
    {
        $answerSession = $this->Session->read('answer');

        if (empty($answerSession['poll'])) {
            // No poll set
            $this->cakeError('pollNotFound');
        }

        $pollId = $answerSession['poll'];


        // Set quest indicator to first if not set
        if (empty($answerSession['questionNum'])) {
            $answerSession['questionNum'] = 1;
        }

        // Create empty answers holder
        if (!isset($answerSession['answers'])) {
            $answerSession['answers'] = array();
        }

        // Current question
        $question = $this->Answer->Question->find(
            'first',
            array(
                'conditions' => array(
                    'Question.poll_id' => $answerSession['poll'],
                    'Question.num' => $answerSession['questionNum']
                )
            )
        );

        if (!empty($this->data) && !empty($this->data['Answer'])) {
            // Store answer to session
            $answerData = $this->data['Answer'];
            $answerData['question_id'] = $question['Question']['id'];

            $answerSession['answers'][] = $answerData;
            $answerSession['questionNum']++;

            $this->data = null;

            // Get next question
            $question = $this->Answer->Question->find(
                'first',
                array(
                    'conditions' => array(
                        'Question.poll_id' => $answerSession['poll'],
                        'Question.num' => $answerSession['questionNum']
                    )
                )
            );
        }

        $this->Session->write('answer', $answerSession);

        // debug($answerSession);

        if (empty($question)) {
            // Just answered to last question
            $this->_finishAnswering();
            return;
        }

        $this->set('question', $question);
        // debug($question);die;


        // Display other answers
        
        if ($question['Question']['answer_visible']) {
            // Display other answers
            $answers = $this->Answer->find(
                'all',
                array(
                    'conditions' => array(
                        'Answer.question_id' => $question['Question']['id']
                    )
                )
            );
            $this->set('answers', $answers);
        }
    
    }


    /**
     * Saves answers and clears session data
     */
    protected function _finishAnswering()
    {
        $answerSession = $this->Session->read('answer');
        foreach ($answerSession['answers'] as $answer) {
            $answer['hash'] = $answerSession['hash'];
            $this->Answer->create(array('Answer' => $answer));
            $this->Answer->save();
        }

        $this->loadModel('Poll');
        $this->Poll->id = $answerSession['poll'];
        $answers = $this->Poll->field('answers');
        $answers++;
        $this->Poll->saveField('answers', $answers);


        $this->Session->write('answer', array());

        $this->render('finish');
    }
}