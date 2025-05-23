<?php

namespace App\Controller;

use App\DTO\CreateMessage;
use App\Entity\User;
use App\Factory\MessageFactory;
use App\Repository\ConversationRepository;
use App\Services\TopicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;


/**
 * @method User |null getUser()
 */
final class MessageController extends AbstractController
{
    public function __construct(
        private MessageFactory         $factory,
        private ConversationRepository $conversationRepository,
        private HubInterface           $hub,
        private TopicService           $topicService
    )
    {
    }

    #[Route('/messages', name: 'message.create', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateMessage $payload): Response
    {
        $conversation = $this->conversationRepository->find($payload->conversationId);

        $message = $this->factory->create(
            conversation: $conversation,
            author: $this->getUser(),
            content: $payload->content
        );

        $data = [
            'authorId' => $message->getAuthor()->getId(),
            'content'=>$message->getContent()
        ];

        $update = new Update(
            topics: $this->topicService->getTopicUrl($conversation),
            data: json_encode($data),
            private: true
        );

        $this->hub->publish($update);

        return new Response('', Response::HTTP_CREATED);
    }
}
