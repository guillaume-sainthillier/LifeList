<?php

namespace LifeList\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use LifeList\ApiBundle\Entity\Liste;
use Symfony\Component\HttpFoundation\Request;
use LifeList\ApiBundle\Form\ListeType;

class ListeController extends FOSRestController {

    public function addListAction(Request $request) {
	$liste = new Liste;
	$form = $this->createForm(new ListeType, $liste);

	$form->bind($request);

	if ($form->isValid()) {
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($liste);
	    $em->flush();

	    $view = $this->view($liste, 200);

	    return $this->handleView($view);
	}

	return $form;
    }

    public function editListAction(Request $request, Liste $liste) {
	$form = $this->createForm(new ListeType, $liste);

	$form->bind($request);

	if ($form->isValid()) {
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($liste);
	    $em->flush();

	    $view = $this->view($liste, 200);

	    return $this->handleView($view);
	}

	return $form;
    }

    public function deleteListAction(Liste $liste) {
	$em = $this->getDoctrine()->getManager();
	$em->remove($liste);
	$em->flush();

	return array("success" => true);
    }

    public function getListsAction() {

	$em = $this->getDoctrine()->getManager();
	$listes = $em->getRepository("LifeListApiBundle:Liste")->findAll();

	$view = $this->view($listes, 200)
		->setTemplate("LifeListApiBundle:Listes:getListes.html.twig")
		->setTemplateVar('todos')
	;

	$response = $this->handleView($view);

	$response->setLastModified(new \DateTime);
	return $response;
    }

}
