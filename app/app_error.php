<?php

class AppError extends ErrorHandler 
{
    public function pollNotFound()
    {
        $this->_outputMessage('poll_not_found');
    }

    public function pollNotPublished()
    {
        $this->_outputMessage('poll_not_published');
    }

    public function invalidHash()
    {
        $this->_outputMessage('invalid_hash');
    }
}