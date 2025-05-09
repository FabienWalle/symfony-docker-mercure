<?php

namespace App\Controller;

use App\Entity\User;
use App\Factory\ConversationFactory;
use App\Repository\ConversationRepository;
use App\Services\TopicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @method User |null getUser()
 */
final class ConversationController extends AbstractController
{
    public function __construct(
        private Authorization          $authorization,
        private ConversationRepository $conversationRepository,
        private ConversationFactory    $factory,
        private Discovery              $discovery,
        private TopicService           $topicService
    )
    {
    }

    #[Route('/conversation/users/{recipient}', name: 'conversation.show')]
    public function show(User $recipient, Request $request): Response
    {
        $sender = $this->getUser();

        $conversation = $this->conversationRepository->findByUsers($sender, $recipient);

        if (!$conversation) {
            $conversation = $this->factory->create($sender, $recipient);
        }

        $topic = $this->topicService->getTopicUrl($conversation);

        $this->discovery->addLink($request);
        $this->authorization->setCookie($request, [$topic]);

        return $this->render('conversation/show.html.twig', [
            'conversation' => $conversation,
            'topic' => $topic
        ]);
    }
}
