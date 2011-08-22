<?php

class AnswersController extends AppController
{
    public $uses = array('Answer', 'Poll');

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

    /**
     * Starts answering process
     */
    public function poll($pollId = null, $hash = null)
    {
        $this->Session->write('answer', array()); // Clear session
    
        if ($pollId != null) {
            $this->Poll->id = $pollId;
            if ($this->Poll->exists()) {

                // Make sure poll is already published
                if (!$this->Poll->field('published')) {
                    $this->cakeError('pollNotPublished');
                }

                if (!$this->Poll->field('public')) {
                    // Validate hash if poll is not public
                    if (!$this->Poll->validHash($hash)) {
                        $this->cakeError('invalidHash');
                    }
                } else {
                    // Use micro timestamp when public poll
                    $hash = microtime();
                    
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
                        'action' => 'welcome'
                    )
                );
            }
        }
        $this->cakeError('pollNotFound');
    }

    public function welcome()
    {
        $answerSession = $this->Session->read('answer');
        if (empty($answerSession['poll'])) {
            $this->cakeError('pollNotFound');
        }

        $this->Poll->recursive = -1;
        $poll = $this->Poll->findById($answerSession['poll']);
        $this->set('poll', $poll);
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
                ),
                'contain' => array(
                    'Poll' => array(
                        'Marker',
                        'Path'
                    )
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
        // debug($question);die;

        $this->Session->write('answer', $answerSession);


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