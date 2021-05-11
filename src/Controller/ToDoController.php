<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;

class ToDoController extends AbstractController
{
    #[Route('/', name: 'to_do')]
    public function index(): Response
    {
                //   Wyszukiwanie zadań z bazy
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([],['id'=>'DESC']);
        return $this->render('index.html.twig', ['tasks' => $tasks]);
    }
    #[Route('/create', name: 'create_task', methods: ['POST'])]
    public function CreateTask(Request $Request)
    {
                // Zapis zadań do bazy
        $title = trim($Request->request->get('task_input'));
        if(empty($title)){
            return $this->redirectToRoute('to_do');
        }
        $entityManager = $this->getDoctrine()->getManager();

        $task = new Task();
        $task->setTitle($title);
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('to_do');

    }
    #[Route('/switch-status/{id}', name: 'switch_status')]
    public function SwitchStatus($id)
    {
        // Zmiana wykonania zadania
         $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);
        $task->setStatus(! $task->getStatus());
        $entityManager->flush();
        return $this->redirectToRoute('to_do');
    }
    #[Route('/delete-task/{id}', name: 'delete_task')]
    public function DeleteTask(Task $id)
    {
        // Usunięcie zadania z param converter
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($id);
        $entityManager->flush();


        return $this->redirectToRoute('to_do');
    }
}
