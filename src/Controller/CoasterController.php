<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Coaster;
use App\Entity\Park;
use App\Form\CoasterType;
use App\Repository\CoasterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CoasterController extends AbstractController
{


    #[Route('/coaster', name: 'coaster_index')]
    public function index(EntityManagerInterface $em, Request $request, CoasterRepository $cr): Response
    {
        $parks = $em->getRepository(Park::class)->findAll();
        $categories = $em->getRepository(Category::class)->findAll();

        $coasters = $cr->findFiltered($request->get("park", ""), $request->get("categories", ""), 1, 25, $request->get("search", ""));
        $pageCount = ceil($coasters->count() / 25);
        return $this->render('coaster/index.html.twig', ["coasters" => $coasters, "parks" => $parks, "categories" => $categories, "pageCount" => $pageCount]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/coaster/add', name: 'coaster_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $entity = new Coaster();
        $form = $this->createForm(CoasterType::class, $entity);
        $form->handleRequest($request);
        $entity->setUser($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('coaster/add.html.twig', ['coasterForm' => $form,]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/coaster/edit/{id}', name: 'coaster_edit')]
    public function edit(Request $request, EntityManagerInterface $em, Coaster $coaster): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $coaster);
        $form = $this->createForm(CoasterType::class, $coaster, ['isAdmin' => $this->isGranted('ROLE_ADMIN')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('coaster/edit.html.twig', ['coasterForm' => $form]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/coaster/delete/{id}', name: 'coaster_delete')]
    public function delete(EntityManagerInterface $em, Coaster $coaster, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $request->get("id"), $request->get('_token'))) {

            $em->remove($coaster);
            $em->flush();
        }
        return $this->redirectToRoute('coaster_index');
    }

}