<?php

namespace LifeList\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use LifeList\ApiBundle\Entity\Todo;
use Symfony\Component\HttpFoundation\Request;
use LifeList\ApiBundle\Form\TodoType;

class TodosController extends FOSRestController {

    public function addTodoAction(Request $request) {

	$em = $this->getDoctrine()->getManager();
	$liste = $this->getListFromRequest($request, $em);

	$todo = new Todo;
	$form = $this->createForm(new TodoType, $todo);

	$form->bind($request);
	if ($form->isValid()) {
	    $todo->setListe($liste);

	    $em->persist($todo);
	    $em->flush();

	    $view = $this->view($todo, 200);

	    return $this->handleView($view);
	}

	return $form;
    }

    protected function getListFromRequest(Request $request, $em)
    {
	$repo = $em->getRepository("LifeListApiBundle:Liste");
	$idList = $request->get("listId");

	$liste = $repo->find($idList);
	$request->request->remove("listId");

	return $liste;
    }

    public function editTodoAction(Request $request, Todo $todo) {
	$form = $this->createForm(new TodoType, $todo);

	$form->bind($request);

	if ($form->isValid()) {
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($todo);
	    $em->flush();

	    $view = $this->view($todo, 200);

	    return $this->handleView($view);
	}

	return $form;
    }

    public function deleteTodoAction(Todo $todo) {
	$em = $this->getDoctrine()->getManager();
	$em->remove($todo);
	$em->flush();

	return array("success" => true);
    }

    public function getTodosAction() {

	$em = $this->getDoctrine()->getManager();
	$todos = $em->getRepository("LifeListApiBundle:Todo")->findAll();

	$view = $this->view($todos, 200)
		->setTemplate("LifeListApiBundle:Todos:getTodos.html.twig")
		->setTemplateVar('todos')
	;

	return $this->handleView($view);
    }

}
