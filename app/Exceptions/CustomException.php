<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    protected $message = 'An error occurred';
    protected $code = 500;

    public function __construct($message = null, $code = null, Exception $previous = null)
    {
        $this->message = $message ?? $this->message;
        $this->code = $code ?? $this->code;

        parent::__construct($this->message, $this->code, $previous);
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $this->message,
                'code' => $this->code
            ], $this->code);
        }

        return response()->view('errors.custom', [
            'message' => $this->message,
            'code' => $this->code
        ], $this->code);
    }
}
