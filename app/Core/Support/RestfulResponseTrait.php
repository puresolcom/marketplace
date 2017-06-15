<?php

namespace Awok\Core\Support;

use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Http\Response;

trait RestfulResponseTrait
{
    protected function jsonResponse(
        $content,
        $message = 'OK',
        $status = 200,
        $errors = []
    ) {

        $message = $this->handleMessage($message);

        $formattedContent = [
            'api'    => [
                'format' => 'json',
            ],
            'status' => [
                'code'    => $status,
                'message' => $message,
            ],
            'output' => [
                'data'   => $content,
                'errors' => $errors,
            ],
        ];

        return $this->response($formattedContent, 200, 'application/json');
    }

    protected function handleMessage($message)
    {
        if ($message instanceof RelationNotFoundException) {
            preg_match('/\[[a-zA-Z ]+\]/', $message->getMessage(), $relation);
            $message = "Relation {$relation[0]} is not a valid relation";
        } elseif ($message instanceof \Exception) {
            $message = $message->getMessage();
        }

        return $message;
    }

    protected function response(
        $content,
        $status = 200,
        $contentType = 'application/json'
    ) {
        $headers = ['Content-Type' => $contentType];

        return new Response($content, $status, $headers);
    }
}