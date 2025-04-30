<?php

namespace App\DTO;

final class CreateMessage
{
    public function __construct(
        public string $content,
        public int    $conversationId
    )
    {
    }
}
