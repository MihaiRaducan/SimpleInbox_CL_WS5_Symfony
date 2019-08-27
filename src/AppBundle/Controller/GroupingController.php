<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Grouping;
use AppBundle\Entity\Person;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Grouping controller.
 * @Security("has_role('ROLE_USER')")
 * @Route("group")
 */
class GroupingController extends Controller
{
    /**
     * Lists all grouping entities.
     *
     * @Route("/", name="group_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groupings = $em->getRepository('AppBundle:Grouping')->findAll();

        return $this->render('grouping/index.html.twig', array(
            'groupings' => $groupings,
        ));
    }

    /**
     * Creates a new grouping entity.
     * @Route("/new", name="group_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $grouping = new Grouping();
        $form = $this->createForm('AppBundle\Form\GroupingType', $grouping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $grouping = $form->getData();

            foreach ($grouping->getPersons() as $selectedPerson) {
                $selectedPerson->addToGrouping($grouping);
                $em->persist($selectedPerson);
            }

            $em->persist($grouping);
            $em->flush();

            return $this->redirectToRoute('group_show', array('id' => $grouping->getId()));
        }

        return $this->render('grouping/new.html.twig', array(
            'grouping' => $grouping,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a grouping entity.
     *
     * @Route("/{id}", name="group_show", methods={"GET"})
     */
    public function showAction(Grouping $grouping)
    {
        $deleteForm = $this->createDeleteForm($grouping);

        return $this->render('grouping/show.html.twig', array(
            'grouping' => $grouping,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing grouping entity.
     *
     * @Route("/{id}/edit", name="group_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Grouping $grouping)
    {
        $deleteForm = $this->createDeleteForm($grouping);
        $editForm = $this->createForm('AppBundle\Form\GroupingType', $grouping);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('group_edit', array('id' => $grouping->getId()));
        }

        return $this->render('grouping/edit.html.twig', array(
            'grouping' => $grouping,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a grouping entity.
     *
     * @Route("/{id}", name="group_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Grouping $grouping)
    {
        $form = $this->createDeleteForm($grouping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($grouping);
            $em->flush();
        }

        return $this->redirectToRoute('group_index');
    }

    /**
     * Creates a form to delete a grouping entity.
     *
     * @param Grouping $grouping The grouping entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Grouping $grouping)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('group_delete', array('id' => $grouping->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
