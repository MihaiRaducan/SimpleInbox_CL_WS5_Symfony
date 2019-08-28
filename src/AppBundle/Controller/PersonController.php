<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Entity\Email;
use AppBundle\Entity\Person;

use AppBundle\Entity\Phone;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Person controller.
 * @Security("has_role('ROLE_USER')")
 * @Route("person")
 */
class PersonController extends Controller
{
    /**
     * Lists all person entities.
     *
     * @Route("/", name="person_index", methods={"GET"})
     */
    public function indexAction(UserInterface $user=null)
    {
        if($user->hasRole('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $people = $em->getRepository('AppBundle:Person')->findAll();
        }
        else {
            $people = $user->getPersons();
        }

        return $this->render('person/index.html.twig', array(
            'people' => $people,
        ));
    }

    /**
     * Creates a new person entity.
     *
     * @Route("/new", name="person_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request, UserInterface $user=null)
    {
        $person = new Person();
        $form = $this->createForm('AppBundle\Form\PersonType', $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $person->setUser($user);
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return $this->render('person/new.html.twig', array(
            'person' => $person,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a person entity.
     *
     * @Route("/{id}", name="person_show", methods={"GET"})
     */
    public function showAction(Person $person, UserInterface $user=null)
    {
        if ($person->getUser() != $user && !$user->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute('person_index');
        }

        $deleteForm = $this->createDeleteForm($person);

        return $this->render('person/show.html.twig', array(
            'person' => $person,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing person entity.
     *
     * @Route("/{id}/edit", name="person_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Person $person, UserInterface $user=null)
    {
        if ($person->getUser() != $user && !$user->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute('person_index');
        }

        $deleteForm = $this->createDeleteForm($person);
        $editForm = $this->createForm('AppBundle\Form\PersonType', $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return $this->render('person/edit.html.twig', array(
            'person' => $person,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a person entity.
     *
     * @Route("/{id}", name="person_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Person $person)
    {
        $form = $this->createDeleteForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($person);
            $em->flush();
        }

        return $this->redirectToRoute('person_index');
    }

    /**
     * Adds an address to the person with {id}.
     *
     * @Route("/{id}/addAddress", name="add_Address", methods={"GET", "POST"})
     */
    public function addAddressAction(Request $request, Person $person, UserInterface $user=null)
    {
        if ($person->getUser() != $user && !$user->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute('person_index');
        }

        $address = new Address();
        $form = $this->createForm('AppBundle\Form\AddressType', $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $person->addAddress($address);
            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return $this->render('address/new.html.twig', array(
            'address' => $address,
            'form' => $form->createView(),
        ));
    }

    /**
     * Adds an email to the person with {id}.
     *
     * @Route("/{id}/addEmail", name="add_Email", methods={"GET", "POST"})
     */
    public function addEmailAction(Request $request, Person $person, UserInterface $user=null)
    {
        if ($person->getUser() != $user && !$user->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute('person_index');
        }

        $email = new Email();
        $form = $this->createForm('AppBundle\Form\EmailType', $email);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $email->setPerson($person);
            $em->persist($email);
            $em->flush();

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return $this->render('email/new.html.twig', array(
            'email' => $email,
            'form' => $form->createView(),
        ));
    }

    /**
     * Adds a phone to the person with {id}.
     *
     * @Route("/{id}/addPhone", name="add_Phone", methods={"GET", "POST"})
     */
    public function addPhoneAction(Request $request, Person $person, UserInterface $user=null)
    {
        if ($person->getUser() != $user && !$user->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute('person_index');
        }

        $phone = new Phone();
        $form = $this->createForm('AppBundle\Form\PhoneType', $phone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $phone->setPerson($person);
            $em->persist($phone);
            $em->flush();

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return $this->render('phone/new.html.twig', array(
            'phone' => $phone,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a person entity.
     *
     * @param Person $person The person entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Person $person)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('person_delete', array('id' => $person->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
