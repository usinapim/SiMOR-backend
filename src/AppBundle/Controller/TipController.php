<?php

namespace AppBundle\Controller;

use RMS\PushNotificationsBundle\Message\AndroidMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Tip;
use AppBundle\Form\TipType;

/**
 * Tip controller.
 *
 */
class TipController extends Controller {

	/**
	 * Lists all Tip entities.
	 *
	 */
	public function indexAction( Request $request ) {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository( 'AppBundle:Tip' )->findAll();

		$paginator = $this->get( 'knp_paginator' );
		$entities  = $paginator->paginate(
			$entities,
			$request->query->get( 'page', 1 )/* page number */,
			10/* limit per page */
		);

		return $this->render( 'AppBundle:Tip:index.html.twig',
			array(
				'entities' => $entities,
			) );
	}

	/**
	 * Creates a new Tip entity.
	 *
	 */
	public function createAction( Request $request ) {
		$entity = new Tip();
		$form   = $this->createCreateForm( $entity );
		$form->handleRequest( $request );

		if ( $form->isValid() ) {
			$em = $this->getDoctrine()->getManager();
			$em->persist( $entity );
			$em->flush();

			$this->get( 'session' )->getFlashBag()->add(
				'success',
				'Tip creado correctamente.'
			);


			$client = $this->get( 'dizda_onesignal_api.service.client' );

			$client->notifications->add( [
				'headings'          => [
					'en' => 'SiMOR',
					'es' => 'SiMOR',
					'pt' => 'SiMOR',
				],
				'contents'          => [
					'en' => $entity->getDescripcion(),
					'es' => $entity->getDescripcion(),
					'pt' => $entity->getDescripcion(),
				],
				'included_segments' => [ 'All' ],


			] );

			return $this->redirect( $this->generateUrl( 'tips_show', array( 'id' => $entity->getId() ) ) );
		}

		return $this->render( 'AppBundle:Tip:new.html.twig',
			array(
				'entity' => $entity,
				'form'   => $form->createView(),
			) );
	}

	/**
	 * Creates a form to create a Tip entity.
	 *
	 * @param Tip $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createCreateForm( Tip $entity ) {
		$form = $this->createForm( new TipType(),
			$entity,
			array(
				'action' => $this->generateUrl( 'tips_create' ),
				'method' => 'POST',
				'attr'   => array( 'class' => 'box-body' )
			) );

		$form->add( 'submit',
			'submit',
			array(
				'label' => 'Crear',
				'attr'  => array( 'class' => 'btn btn-primary pull-right' )
			) );

		return $form;
	}

	/**
	 * Displays a form to create a new Tip entity.
	 *
	 */
	public function newAction() {
		$entity = new Tip();
		$form   = $this->createCreateForm( $entity );

		return $this->render( 'AppBundle:Tip:new.html.twig',
			array(
				'entity' => $entity,
				'form'   => $form->createView(),
			) );
	}

	/**
	 * Finds and displays a Tip entity.
	 *
	 */
	public function showAction( $id ) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository( 'AppBundle:Tip' )->find( $id );

		if ( ! $entity ) {
			throw $this->createNotFoundException( 'Unable to find Tip entity.' );
		}

		$deleteForm = $this->createDeleteForm( $id );

		return $this->render( 'AppBundle:Tip:show.html.twig',
			array(
				'entity'      => $entity,
				'delete_form' => $deleteForm->createView(),
			) );
	}

	/**
	 * Displays a form to edit an existing Tip entity.
	 *
	 */
	public function editAction( $id ) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository( 'AppBundle:Tip' )->find( $id );

		if ( ! $entity ) {
			throw $this->createNotFoundException( 'Unable to find Tip entity.' );
		}

		$editForm   = $this->createEditForm( $entity );
		$deleteForm = $this->createDeleteForm( $id );

		return $this->render( 'AppBundle:Tip:edit.html.twig',
			array(
				'entity'      => $entity,
				'edit_form'   => $editForm->createView(),
				'delete_form' => $deleteForm->createView(),
			) );
	}

	/**
	 * Creates a form to edit a Tip entity.
	 *
	 * @param Tip $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm( Tip $entity ) {
		$form = $this->createForm( new TipType(),
			$entity,
			array(
				'action' => $this->generateUrl( 'tips_update', array( 'id' => $entity->getId() ) ),
				'method' => 'PUT',
				'attr'   => array( 'class' => 'box-body' )
			) );

		$form->add(
			'submit',
			'submit',
			array(
				'label' => 'Actualizar',
				'attr'  => array( 'class' => 'btn btn-primary pull-right' ),
			)
		);

		return $form;
	}

	/**
	 * Edits an existing Tip entity.
	 *
	 */
	public function updateAction( Request $request, $id ) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository( 'AppBundle:Tip' )->find( $id );

		if ( ! $entity ) {
			throw $this->createNotFoundException( 'Unable to find Tip entity.' );
		}

		$deleteForm = $this->createDeleteForm( $id );
		$editForm   = $this->createEditForm( $entity );
		$editForm->handleRequest( $request );

		if ( $editForm->isValid() ) {
			$em->flush();

			$this->get( 'session' )->getFlashBag()->add(
				'success',
				'Tip actualizado correctamente.'
			);

			return $this->redirect( $this->generateUrl( 'tips_edit', array( 'id' => $id ) ) );
		}

		return $this->render( 'AppBundle:Tip:edit.html.twig',
			array(
				'entity'      => $entity,
				'edit_form'   => $editForm->createView(),
				'delete_form' => $deleteForm->createView(),
			) );
	}

	/**
	 * Deletes a Tip entity.
	 *
	 */
	public function deleteAction( Request $request, $id ) {
		$form = $this->createDeleteForm( $id );
		$form->handleRequest( $request );

		if ( $form->isValid() ) {
			$em     = $this->getDoctrine()->getManager();
			$entity = $em->getRepository( 'AppBundle:Tip' )->find( $id );

			if ( ! $entity ) {
				throw $this->createNotFoundException( 'Unable to find Tip entity.' );
			}

			$em->remove( $entity );
			$em->flush();
		}

		return $this->redirect( $this->generateUrl( 'tips' ) );
	}

	/**
	 * Creates a form to delete a Tip entity by id.
	 *
	 * @param mixed $id The entity id
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm( $id ) {
		return $this->createFormBuilder()
		            ->setAction( $this->generateUrl( 'tips_delete', array( 'id' => $id ) ) )
		            ->setMethod( 'DELETE' )
		            ->add( 'submit', 'submit', array( 'label' => 'Delete' ) )
		            ->getForm();
	}
}
