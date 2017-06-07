<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Medida;
use AppBundle\Form\MedidaType;

/**
 * Medida controller.
 *
 */
class MedidaController extends Controller
{

    /**
     * Lists all Medida entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

//        $entities = $em->getRepository('AppBundle:Medida')->findAll();
        $entities = $em->getRepository('AppBundle:Medida')->getQbOrdenada();

        $paginator = $this->get('knp_paginator');
        $entities = $paginator->paginate(
	        $entities, $request->query->get('page', 1)/* page number */, 10/* limit per page */
        );

        return $this->render('AppBundle:Medida:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Medida entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Medida();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success', 'Medida creado correctamente.'
            );

            return $this->redirect($this->generateUrl('medidas_show', array('id' => $entity->getId())));
        }

        return $this->render('AppBundle:Medida:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Medida entity.
     *
     * @param Medida $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Medida $entity)
    {
        $form = $this->createForm(new MedidaType(), $entity, array(
            'action' => $this->generateUrl('medidas_create'),
            'method' => 'POST',
            'attr' => array('class' => 'box-body')
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Crear',
            'attr' => array('class' => 'btn btn-primary pull-right')
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Medida entity.
     *
     */
    public function newAction()
    {
        $entity = new Medida();
        $form   = $this->createCreateForm($entity);

        return $this->render('AppBundle:Medida:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Medida entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Medida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Medida entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Medida:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Medida entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Medida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Medida entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Medida:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Medida entity.
    *
    * @param Medida $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Medida $entity)
    {
        $form = $this->createForm(new MedidaType(), $entity, array(
            'action' => $this->generateUrl('medidas_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'box-body')
        ));

        $form->add(
            'submit',
            'submit',
            array(
                'label' => 'Actualizar',
                'attr' => array('class' => 'btn btn-primary pull-right'),
            )
        );

        return $form;
    }
    /**
     * Edits an existing Medida entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Medida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Medida entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success', 'Medida actualizado correctamente.'
            );

            return $this->redirect($this->generateUrl('medidas_edit', array('id' => $id)));
        }

        return $this->render('AppBundle:Medida:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Medida entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Medida')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Medida entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('medidas'));
    }

    /**
     * Creates a form to delete a Medida entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('medidas_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
