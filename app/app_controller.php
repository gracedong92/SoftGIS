<?php

class AppController extends Controller 
{
    public $components = array(
        'Auth' => array(
            // 'authorize' => 'actions',
            'userModel' => 'Author',
            'loginAction' => array(
                'controller' => 'authors',
                'action' => 'login'
            ),
            'loginRedirect' => array(
                'controller' => 'polls',
                'action' => 'index'
            )
        ),
        'Session',
        'RequestHandler'
    );

    public $helpers = array(
        'Html',
        'Js',
        'Session'
    );
}