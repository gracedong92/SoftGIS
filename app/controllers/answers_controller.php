<?php

class AnswersController extends AppController
{
    public function index($questionNum = null)
    {
        if (!$this->Session->check('answer.poll')) {
            $this->redirect(array('controller' => 'polls'));
        }

        $pollId = $this->Session->read('answer.poll');

        if ($questionNum == null) {
            if ($this->Session->check('answer.num')) {
                $questionNum = $this->Session->read('answer.num');
            } else {
                $questionNum = 1;
            }
        }

        $question = $this->Answer->Question->find(
            'first',
            array(
                'conditions' => array(
                    'Question.poll_id' => $pollId,
                    'Question.num' => $questionNum
                )
            )
        );

        if (empty($question)) {
            throw Exception('Kysymystä ei löytynyt');
        }

        $this->set('question', $question);

        if ($question['Question']['answer_visible']) {
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
}