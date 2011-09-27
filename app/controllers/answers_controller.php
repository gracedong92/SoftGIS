<?php

class AnswersController extends AppController
{
    public $uses = array('Answer', 'Poll', 'Response', 'Hash');

    public function beforeFilter()
    {
        parent::beforeFilter();
        // error_reporting(E_ALL);
        $this->Auth->allow('index', 'poll', 'welcome', 'answer');
    }

    public function index($pollId = null, $hash = null)
    {
        $this->Poll->id = $pollId;
        if ($this->Poll->exists()) {

            // Make sure poll is active
            $launch = $this->Poll->field('launch');
            $end = $this->Poll->field('end');

            if (!$launch || strtotime($launch) > time()) {
                $this->cakeError('pollNotPublished');
            } else if($end && strtotime('+1 day', strtotime($end)) < time()) {
                $this->cakeError('pollClosed');
            }

            if (!$this->Poll->field('public')) {
                // Validate hash if poll is not public
                if (!$this->Poll->validHash($hash)) {
                    $this->cakeError('invalidHash');
                }
            } 

            $this->Session->write(
                'answer', 
                array(
                    'poll' => $pollId,
                    'hash' => $hash,
                    'test' => false
                )
            );
            $this->redirect(array('action' => 'answer'));
        } else {
            $this->cakeError('pollNotFound');
        }
    }

    /**
     * Authed only action for testing polls
     *
     */
    public function test($pollId = null)
    {
        $this->Session->write('answer', array()); // Clear session
        $hash = null;
        $this->Poll->id = $pollId;

        if ($this->Poll->exists()) {
            $this->Session->write(
                'answer', 
                array(
                    'poll' => $pollId,
                    'hash' => $hash,
                    'test' => true
                )
            );

            $this->redirect(array('action' => 'answer'));
        } else {
            $this->cakeError('pollNotFound');
        }
    }

    // /**
    //  * Starts answering process
    //  */
    // public function poll($pollId = null, $hash = null)
    // {
    //     $this->Session->write('answer', array()); // Clear session
    
    //     if ($pollId != null) {
    //         $this->Poll->id = $pollId;
    //         if ($this->Poll->exists()) {

    //             // Make sure poll is already published
    //             if (!$this->Poll->field('published')) {
    //                 $this->cakeError('pollNotPublished');
    //             }

    //             if (!$this->Poll->field('public')) {
    //                 // Validate hash if poll is not public
    //                 if (!$this->Poll->validHash($hash)) {
    //                     $this->cakeError('invalidHash');
    //                 }
    //             } 

    //             $this->Session->write(
    //                 'answer', 
    //                 array(
    //                     'poll' => $pollId,
    //                     'hash' => $hash
    //                 )
    //             );

    //             $this->redirect(
    //                 array(
    //                     'action' => 'welcome'
    //                 )
    //             );
    //         }
    //     }
    //     $this->cakeError('pollNotFound');
    // }

    public function answer()
    {
        $session = $this->Session->read('answer');
        if (empty($session)) {
            $this->cakeError('pollNotFound');
        }

        $this->Poll->contain('Marker', 'Path', 'Question', 'Response');
        $this->Poll->id = $session['poll'];
        $poll = $this->Poll->read();
        // debug($poll);die;
        $this->set('poll', $poll);
        
    }

    public function finish()
    {
        $data = json_decode($this->data);

        $session = $this->Session->read('answer');
        if (empty($session)) {
            $this->cakeError('pollNotFound');
        }
        $this->Poll->id = $session['poll'];
        $this->Poll->contain('Question');
        $poll = $this->Poll->read();

        // debug($session);die;

        if ($session['test'] == 0) {
            // Not test answer, update db
            $hash = $session['hash'];

            // Create response entry
            $this->Response->create(
                array(
                    'poll_id' => $session['poll'],
                    'created' => date('Y-m-d H:i:s'),
                    'hash' => $session['hash']
                )
            );
            $this->Response->save();
            $responseId = $this->Response->id;

            foreach ($data as $i => $a) {
                if (!isset($a->text) || !isset($a->loc)) {
                    break;
                }
                if (!isset($poll['Question'][$i])) {
                    break;
                }
                $question = $poll['Question'][$i];

                if ($question['answer_location'] == 1) {
                    $latLng = explode(',', $a->loc);
                    $lat = isset($latLng[0]) ? (float)$latLng[0] : "";
                    $lng = isset($latLng[1]) ? (float)$latLng[1] : "";
                } else {
                    $lat = '';
                    $lng = '';
                }

                $this->Answer->create(
                    array(
                        'response_id' => $responseId,
                        'question_id' => $question['id'],
                        'answer' => strip_tags(trim($a->text)),
                        'lat' => $lat,
                        'lng' => $lng
                    )
                );
                $this->Answer->save();
            }

            // Tag hash as used
            if (!empty($session['hash'])) {
                $hashEntry = $this->Hash->findByHash($hash);
                $hashEntry['Hash']['used'] = 1;
                $this->Hash->save($hashEntry);
            }
        }

        // Clear answer session
        $this->Session->write('answer', array());

        $this->set('poll', $poll);
        $this->set('test', $session['test']);
        $this->render('finish');
    }

    // public function welcome()
    // {
    //     $answerSession = $this->Session->read('answer');
    //     if (empty($answerSession['poll'])) {
    //         $this->cakeError('pollNotFound');
    //     }

    //     $this->Poll->recursive = -1;
    //     $poll = $this->Poll->findById($answerSession['poll']);
    //     $this->set('poll', $poll);
    // }

    // public function answer()
    // {
    //     $answerSession = $this->Session->read('answer');

    //     if (empty($answerSession['poll'])) {
    //         // No poll set
    //         $this->cakeError('pollNotFound');
    //     }

    //     $pollId = $answerSession['poll'];


    //     // Set quest indicator to first if not set
    //     if (empty($answerSession['questionNum'])) {
    //         $answerSession['questionNum'] = 1;
    //     }

    //     // Create empty answers array
    //     if (!isset($answerSession['answers'])) {
    //         $answerSession['answers'] = array();
    //     }

    //     // Current question
    //     $question = $this->Answer->Question->find(
    //         'first',
    //         array(
    //             'conditions' => array(
    //                 'Question.poll_id' => $answerSession['poll'],
    //                 'Question.num' => $answerSession['questionNum']
    //             ),
    //             'contain' => array(
    //                 'Poll' => array(
    //                     'Marker',
    //                     'Path'
    //                 )
    //             )
    //         )
    //     );

    //     if (!empty($this->data) && !empty($this->data['Answer'])) {
    //         // Store answer to session
    //         $answerData = $this->data['Answer'];
    //         $answerData['question_id'] = $question['Question']['id'];

    //         $answerSession['answers'][] = $answerData;
    //         $answerSession['questionNum']++;

    //         $this->data = null;

    //         // Get next question
    //         $question = $this->Answer->Question->find(
    //             'first',
    //             array(
    //                 'conditions' => array(
    //                     'Question.poll_id' => $answerSession['poll'],
    //                     'Question.num' => $answerSession['questionNum']
    //                 ),
    //                 'contain' => array(
    //                     'Poll' => array(
    //                         'Marker',
    //                         'Path'
    //                     )
    //                 )
    //             )
    //         );
    //     }
    //     // debug($question);die;

    //     $this->Session->write('answer', $answerSession);


    //     if (empty($question)) {
    //         // Just answered to last question
    //         $this->_finishAnswering();
    //         return;
    //     }

    //     // debug($question);die;
    //     $this->set('question', $question['Question']);
    //     $this->set('markers', $question['Poll']['Marker']);
    //     $this->set('paths', $question['Poll']['Path']);
    //     // $this->set('poll', $question['Poll']);


    //     // Display other answers
        
    //     if ($question['Question']['answer_visible']) {
    //         // Display other answers
    //         $this->Answer->recursive = -1;
    //         $answers = $this->Answer->find(
    //             'all',
    //             array(
    //                 'conditions' => array(
    //                     'Answer.question_id' => $question['Question']['id']
    //                 )
    //             )
    //         );
    //         // debug($answers);die;
    //         $this->set('answers', $answers);
    //     }
    
    // }


    // /**
    //  * Saves answers if not test answer session. 
    //  * 
    //  * Also clears session data
    //  */
    // protected function _finishAnswering()
    // {
    //     $pollId = $this->Session->read('answer.poll');
    //     $isTest = $this->Session->read('answer.test');

    //     $this->Poll->id = $pollId;
    //     $poll = $this->Poll->read();

    //     if (!$isTest) {
    //         // Not test answer, update db

    //         $pollId = $this->Session->read('answer.poll');
    //         $hash = $this->Session->read('answer.hash');

    //         // Create response entry
    //         $this->Response->create(
    //             array(
    //                 'poll_id' => $pollId,
    //                 'created' => date('Y-m-d H:i:s'),
    //                 'hash' => $hash
    //             )
    //         );
    //         $this->Response->save();
    //         $responseId = $this->Response->id;


    //         // Create answer entries
    //         $answers = $this->Session->read('answer.answers');
    //         foreach ($answers as $answer) {
    //             $answer['response_id'] = $responseId;
    //             $this->Answer->create($answer);
    //             $this->Answer->save();
    //         }

    //         // Tag hash as used
    //         if (!empty($hash)) {
    //             $hashEntry = $this->Hash->findByHash($hash);
    //             $hashEntry['Hash']['used'] = 1;
    //             $this->Hash->save($hashEntry);
    //         }
    //     }

    //     // Clear answer session
    //     $this->Session->write('answer', array());

    //     $this->set('poll', $poll);
    //     $this->set('test', $isTest);
    //     $this->render('finish');
    // }
}