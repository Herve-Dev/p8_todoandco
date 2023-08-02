<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function Symfony\Component\Clock\now;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function listAction(TaskRepository $taskRepository): Response
    {
        $userConnected = $this->getUser();
        $tasks = $userConnected->getTask();
        
        //$tasks = $taskRepository->findBy(['user' => $userConnected]);



        return $this->render('task/list.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $tasks
        ]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(
        Request $request,
        EntityManagerInterface $em)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setCreatedAt(now());
            $task->setIsDone(false);
            $task->setUser($this->getUser());

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route("/tasks/{id}/edit", name: 'task_edit')]
    public function editAction(
        Task $task, 
        Request $request,
        EntityManagerInterface $em)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route("/tasks/{id}/toggle", name: 'task_toggle')]
    public function toggleTaskAction(Task $task, EntityManagerInterface $em)
    {
        $isDoneBeforeToggle = $task->isDone();
        $task->toggle(!$isDoneBeforeToggle);
        
        $em->flush();

        if ($isDoneBeforeToggle && !$task->isDone()) {
            $this->addFlash('warning', sprintf('La tâche %s est maintenant marquée non faite.', $task->getTitle()));
        } elseif (!$isDoneBeforeToggle && $task->isDone()) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        }

        return $this->redirectToRoute('task_list');
    }

    #[Route("/tasks/{id}/delete", name: 'task_delete')]
    public function deleteTaskAction(Task $task, EntityManagerInterface $em)
    {
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }

    #[Route("/tasks/finish", name: 'task_finish')]
    public function finishTasks()
    {
        $userTask = $this->getUser();

        $doneTasks = array_filter($userTask->getTask()->toArray(), function ($task) {
            return $task->isDone();
        });

        return $this->render('task/finish.html.twig', [
            'doneTasks' => $doneTasks,
        ]);

    }
}
