<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Mots;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddMotsType;

class MotsController extends AbstractController
{
    /**
     * @Route("/mots", name="mots")
     */
    public function index(Request $request): Response
    {
        $mot= new Mots();
        $form = $this->createForm(AddMotsType::class,$mot);
        $mots = $this->getDoctrine()
        ->getRepository(Mots::class)
        ->findAll();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            

          
            $mot = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mot);
            $entityManager->flush();
            return $this->redirectToRoute('mots');
        }
        return $this->render('mots/index.html.twig', [
            'form' => $form->createView(),
            'mots'=>$mots        ]);
    }

    /**
     * @Route("mots/supprmot/{id}",name="supprmot")
     * 
     */
    public function supprmot(int $id){
        $mot = $this->getDoctrine()
        ->getRepository(Mots::class)
        ->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mot);
        $entityManager->flush();
        return $this->redirectToRoute('mots');
    }
}
