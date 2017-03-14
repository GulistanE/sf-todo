<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use AppBundle\Form\TodoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class TodoController
 * @package AppBundle\Controller
 */
class TodoController extends Controller
{
    /**
     * Index action.
     */
    public function indexAction()
    {
        $form = $this
            ->createForm(new TodoType(),null, [
            'action' => $this->generateUrl('todo_add'),
            ]);

        $em = $this
            ->getDoctrine()
            ->getManager();

        $todos = $em
            ->getRepository('AppBundle:Todo')
            ->findBy( ['trashed'=>false], ['date' => 'DESC'] );

        return $this->render('AppBundle:Todo:index.html.twig', [
            'todos' => $todos,
            'form' => $form->createView(),
        ]);
    }

    public function addAction(Request $request)
    {
        $todo = new Todo();

        $form = $this
            ->createForm(new TodoType(), $todo)
            ->add('submit', 'submit', [
                'label' => 'Ajouter',
                'attr'  => [
                'class' => 'btn btn-warning'
                ]
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush($todo);

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('AppBundle:Todo:index.html.twig', array(
            'todo' => $todo,
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request)
    {
        $todo = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($request->attributes->get('id'));

        $formAction = $this->generateUrl('todo_edit', [
            'id' => $todo->getId(),
        ]);

        $form = $this
            ->createForm(new TodoType(), $todo, [
                'action' => $formAction,
            ])
            ->add('submit', 'submit', [
                'label' => 'Modifier',
                'attr'  => [
                'class' => 'btn btn-warning'
                ]
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            $this->addFlash('success', 'Le todo a bien été modifié.');
        }

        $todos = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->findBy([], ['date' => 'DESC']);

        return $this->render('AppBundle:Todo:index.html.twig', [
            'todos' => $todos,
            'form' => $form->createView(),
        ]);
	}

    public function archiveAction($id)
    {
    $em = $this->getDoctrine()->getManager();
	
    $todo = $em
        ->getRepository('AppBundle:Todo')
        ->find($id);

        if (!$todo) {
            throw $this->createNotFoundException('Todo not found');
        }
		
		$todo->setTrashed(0);
		$em->flush();
		
		return $this->redirectToRoute('todo_index', ['trashed' => 0]);
	}

    public function restoreAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$todo = $em
			->getRepository('AppBundle:Todo')
			->find($id);

		if (!$todo) {
			throw $this->createNotFoundException('Todo not found');
		}
		
		$todo->setTrashed(1);
		$em->flush();
		
		return $this->redirectToRoute('todo_index', ['trashed' => 1]);
	}

    public function trashAction(Request $request)
    {
        $todo = $this->findTodoByRequest($request);

        $form = $this->createDeleteConfirmationForm($todo, [
            'action' => $this->generateUrl('todo_trash', [
                'todoId' => $todo->getTodo()->getId(),
            ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush($todo);

            $this->addFlash('success', 'Le todo a bien été archivé.');

            return $this->redirectToRoute('todo_trash', array('id' => $todo->getId()));
        }

        return $this->render('AppBundle:Todo:index.html.twig', [
            'todo' => $todo,
            'form' => $form->createView(),
        ]);
    }

    private function findToDoForList()
    {
        return $this 
            ->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->findBy(['date' => 'DESC']);
    }
}
