<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Entity\Participant;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/messages", name="messages.")
 */
class MessageController extends AbstractController
{

    const ATTRIBUTES_TO_SERIALIZE = ['id', 'content', 'createdAt', 'mine'];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MessageRepository
     */
    private $messageRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ConversationRepository
     */
    private $conversationRepository;

    public function __construct(EntityManagerInterface $entityManager,
                                MessageRepository $messageRepository,
                                UserRepository $userRepository, ConversationRepository $conversationRepository)
    {
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * @Route("/{id}", name="getMessages", methods={"GET"})
     * @param Request $request
     * @param Conversation $conversation
     * @return Response
     */
    public function index(Request $request, Conversation $conversation, ConversationRepository $ConversationRepository)
    {
        // can i view the conversation

        $this->denyAccessUnlessGranted('view', $conversation);

        $messages = $this->messageRepository->findMessageByConversationId(
            $conversation->getId()
        );

        /**
         * @var $message Message
         */
        array_map(function ($message) {
            $message->setMine(
                $message->getUser()->getId() === $this->getUser()->getId()
                    ? true : false
            );
        }, $messages);
      
        // return $this->json($messages, Response::HTTP_OK, [], [
        //     'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        // ]);
        $conversations = $this->conversationRepository->findConversationsByUser($this->getUser()->getId());

        return $this->render('dashboard/chat.html.twig', [
            'messages' => $messages,
            'conversations'=>  $conversations,             
        ]);
    }

    /**
     * @Route("/{id}", name="newMessage", methods={"POST"})
     * @param Request $request
     * @param Conversation $conversation
     * @return JsonResponse
     * @throws \Exception
     */
    public function newMessage(Request $request, Conversation $conversation)
    {
        // TODO: put everything back
        $user = $this->getUser();
        $content = $request->get('content', null);
        $message = new Message();
        $message->setContent($content);
        $message->setUser($user);
        $message->setMine(true);

        $conversation->addMessage($message);
        $conversation->setLastMessage($message);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($message);
            $this->entityManager->persist($conversation);
            $this->entityManager->flush();
            $this->entityManager->commit();
            return new JsonResponse($message); 
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        $messages = $this->messageRepository->findMessageByConversationId(
            $conversation->getId()
        );

        /**
         * @var $message Message
         */
        array_map(function ($message) {
            $message->setMine(
                $message->getUser()->getId() === $this->getUser()->getId()
                    ? true : false
            );
        }, $messages);

        $conversations = $this->conversationRepository->findConversationsByUser($this->getUser()->getId());
        return $this->render('dashboard/chat.html.twig', [
            'messages' => $messages , 
            'conversations' => $conversations,           
        ]);
        //return flash()->addSuccess('Thank you! Document Submit successfully');
        // return $this->json($message, Response::HTTP_CREATED, [], [
        //     'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        // ]);
    }
}
