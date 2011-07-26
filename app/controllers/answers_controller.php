<?php

class AnswersController extends AppController
{
    public function answer()
    {
        error_reporting(E_ALL);
        $answerSession = $this->Session->read('answer');

        if (empty($answerSession['poll'])) {
            // No poll set
            throw new Exception('Kyselyä ei löytynyt');
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

        debug($answerSession);

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

    protected function _finishAnswering()
    {
        $pollId = $this->Session->read('answer.poll');
        throw new Exception('Kesken');
    }
}