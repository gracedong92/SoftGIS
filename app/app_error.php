<?php

class AppError extends ErrorHandler 
{
    public function pollNotFound()
    {
        $this->_outputMessage('poll_not_found');
    }
}