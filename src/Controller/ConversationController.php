<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Repository\ConversationRepository;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/conversations", name="conversations.")
 */
class ConversationController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ConversationRepository
     */
    private $conversationRepository;

    public function __construct(UserRepository $userRepository,
                                EntityManagerInterface $entityManager,
ConversationRepository $conversationRepository, ParticipantRepository $ParticipantRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->conversationRepository = $conversationRepository;
        $this->ParticipantRepository = $ParticipantRepository;
    }

    /**
     * @Route("/", name="newConversations", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $otherUser = $request->get('otherUser', 0);
        $otherUser = $this->userRepository->find($otherUser);

        if (is_null($otherUser)) {
            throw new \Exception("The user was not found");
        }

        // cannot create a conversation with myself
        if ($otherUser->getId() === $this->getUser()->getId()) {
            throw new \Exception("That's deep but you cannot create a conversation with yourself");
        }

        // Check if conversation already exists
        $conversation = $this->conversationRepository->findConversationByParticipants(
            $otherUser->getId(),
            $this->getUser()->getId()
        );

        if (count($conversation)) {
            throw new \Exception("The conversation already exists");
        }

        $conversation = new Conversation();

        $participant = new Participant();
        $participant->setUser($this->getUser());
        $participant->setConversation($conversation);


        $otherParticipant = new Participant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($conversation);
            $this->entityManager->persist($participant);
            $this->entityManager->persist($otherParticipant);

            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        $messages=[];
        // return $this->json([
        //     'id' => $conversation->getId()
        // ], Response::HTTP_CREATED, [], []);
        return $this->render('dashboard/chat.html.twig', [
            'messages' => $messages , 
            'id'=>  $conversation->getId(),        
        ]);
    }
    
    
    /**
     * @Route("/", name="getConversations", methods={"GET"})
     */
    public function getConvs() {
        $conversations = $this->conversationRepository->findConversationsByUser($this->getUser()->getId());
        $Participant = $this->ParticipantRepository->findby([]);

        $otheruser=[];
        $i=0;
        foreach ($conversations as $key => $value) {
            foreach ($Participant as $key => $va) {
                if($va->getConversation()->getId() == $value['conversationId'] && $va->getUser()->getId() != $this->getUser()->getId() ){
                    $otheruser[$i] =$va->getUser();
                }
                $i++;
            }
        }
        
        //return $this->json($result);
        $messages=[];
        // return $this->json([
        //     'id' => $conversation->getId()
        // ], Response::HTTP_CREATED, [], []);
        return $this->render('dashboard/chatconvers.html.twig', [
            'messages' => $messages , 
            'conversations'=>  $conversations, 
            'otheruser' =>$otheruser,
        ]);
    }

}
