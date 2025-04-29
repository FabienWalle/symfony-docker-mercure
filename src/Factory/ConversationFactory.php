<?php

namespace App\Factory;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\ConversationRepository;


/**
 * @method User |null getUser()
 */
class ConversationFactory
{
    public function __construct(private ConversationRepository $conversationRepository)
    {
    }

    public function create(User $sender, User $recipient): Conversation
    {
        $conversation = new Conversation();

        $conversation->addUser($sender);
        $conversation->addUser($recipient);

        $this->conversationRepository->save($conversation);

        return $conversation;
    }
}
