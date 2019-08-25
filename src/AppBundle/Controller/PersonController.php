<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Entity\Person;
use AppBundle\Form\AddressType;
use AppBundle\Form\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends Controller
{
    /**
     * @Route("/new")
     */
    public function newAction(Request $request){

        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            return $this->render('@App/Person/new.html.twig', array(
                'text' => 'Person added to the database',
                'form' => $form->createView()
            ));
        }

        return $this->render('@App/Person/new.html.twig', array(
            'text' => 'Fill in the values',
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/modify")
     */
    public function modifyAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Person');
        $person = $repository->find($id);

        if (!$person) {
            return $this->render('@App/Person/modify.html.twig', array(
                'text' => 'Person with this ID not found in the database'));
        }

        $form = $this->createForm(PersonType::class, $person);
        if ($request->getMethod() === 'GET') {
            return $this->render('@App/Person/new.html.twig', array(
                'text' => 'Enter new data',
                'form' => $form->createView(),
                'id' => $id
            ));
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            return $this->render('@App/Person/modify.html.twig', array(
                'text' => 'Person data updated in the database'
            ));
        }

        return $this->render('@App/Person/modify.html.twig', array(
            'text' => 'Invalid data or some other error'
        ));
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')->find($id);
        if (!$person) {
            $returnMessage = 'Person with ID: ' . $id . ' not found in the database';
            $form = false;
        }
        else {
            if ($request->getMethod() === 'GET') {
                $returnMessage = 'Are you sure you want to delete person with ID: ' . $id . '?';
                $form = true;
            }
            if ($request->getMethod() === 'POST') {
                $returnMessage = 'Person with ID: ' . $id . ' was deleted';
                $em->remove($person);
                $em->flush();
                $form = false;
            }
        }
        return $this->render('@App/Person/delete.html.twig', array(
            'text' => $returnMessage, 'form' => $form
        ));
    }

    /**
     * @Route("/{id}/addAddress")
     */
    public function addAddressAction (Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Person');
        $person = $repository->find($id);
        $personAddresses = $person->getAddresses();

        if (!$person) {
            return $this->render('@App/Person/modify.html.twig', array(
                'text' => 'Person with this ID not found in the database'));
        }

        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        if ($request->getMethod() === 'GET') {
            return $this->render('@App/Person/address.html.twig', array(
                'name' => $person->getFirstName() . ' ' . $person->getLastName(),
                'form' => $form->createView()
            ));
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $personAddresses[] = $address;
            $em = $this->getDoctrine()->getManager();
            $person->setAddresses($personAddresses);
            $em->persist($address);
            $em->persist($person);
            $em->flush();
            return $this->render('@App/Person/modify.html.twig', array(
                'text' => 'Person data updated in the database'
            ));
        }

        return $this->render('@App/Person/modify.html.twig', array(
            'text' => 'Invalid data or some other error'
        ));
    }

    /**
     * @Route("/{id}")
     */
    public function displayOneByIdAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Person');
        $personToShow = $repository->find($id);

        if (!$personToShow) {
            return $this->render('@App/Person/modify.html.twig', array(
                'text' => 'Person with this ID not found in the database'));
        }
        else {
            $message = '';
            $addresses = $personToShow->getAddresses();
        }

        return $this->render('@App/Person/display_one_by_id.html.twig', array(
            'message' => $message, 'person' => $personToShow, 'addresses' => $addresses
        ));
    }

    /**
     * @Route("/")
     */
    public function displayAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Person');
        $persons = $repository->findAll();

        return $this->render('@App/Person/display_all.html.twig', array(
            'persons' => $persons
        ));
    }
}
