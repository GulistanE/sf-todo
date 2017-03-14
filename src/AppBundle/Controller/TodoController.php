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
            ->findBy( [], ['date' => 'DESC'] );

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
            ->add('save', new SubmitType(), [
                'label' =>'Ajouter',
                'attr' => ['class' => 'btn btn-sm btn-success',]
                ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush($todo);

            return $this->redirectToRoute('todo_new', array('id' => $todo->getId()));
        }

        $todos = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->findBy([], ['date' => 'DESC']);

        return $this->render('AppBundle:App:index.html.twig', array(
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

    public function removeAction(Request $request)
    {
        $todo = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($request->attributes->get('id'));

         $form = $this->removeConfirmationForm($todo, [
            'action' => $this->generateUrl('todo_remove', [
                'todoId' => $todo->getTodo()->getId(),
            ]),
        ]);

        $form = $this
            ->createForm(new TodoType(), $todo, [
                'action' => $formAction,
            ])
            ->add('submit', 'submit', [
                'label' => 'Supprimer',
                'attr'  => [
                'class' => 'btn btn-warning'
                ]
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            $this->addFlash('success', 'Le todo a bien été supprimé.');
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

    public function trashAction(Request $request)
    {
        $todo = $this->findTodoByRequest($request);

        $form = $this->restoreConfirmationForm($todo, [
            'action' => $this->generateUrl('todo_trash', [
                'todoId' => $todo->getTodo()->getId(),
            ]),
        ]);

        $form = $this
            ->createForm()
            ->add('submit', 'submit', [
                'label' => 'Archiver',
                'attr' => [
                    'class' => 'btn btn-warning'
                ]
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
