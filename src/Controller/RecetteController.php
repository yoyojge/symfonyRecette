<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;

// #[Route('/recette')]
class RecetteController extends AbstractController
{
    #[Route('/', name: 'app_recette_index', methods: ['GET'])]
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }

    #[Route('/recette/new', name: 'app_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecetteRepository $recetteRepository): Response
    {
       
        
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            // $userId = $this->getUser()->getId();
            // dd($userId );

            $recette->setUser($this->getUser());
            $recetteRepository->save($recette, true);

            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }




    #[Route('/recette/{id}', name: 'app_recette_show', methods: ['GET', 'POST'])]
    public function show(Recette $recette, Request $request,  CommentaireRepository $commentaireRepository, NoteRepository $noteRepository): Response
    {
        
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);        

        //recuperer tous les commentaires pour une recette
        $commentaires = $commentaireRepository->findBy( array('recette' => $recette ) );
        // dd( $commentaires);

        if ($form->isSubmitted() && $form->isValid()) {
           
            //recuperer user + recette
            $commentaire->setUser($this->getUser());
            $commentaire->setRecette($recette);          
            // dd($recette->getId() );
            $commentaireRepository->save($commentaire, true);           

            return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId() ], Response::HTTP_SEE_OTHER);
         
        }    


        $note = new Note();
        $formNote = $this->createForm(NoteType::class, $note);
        $formNote->handleRequest($request);

        if ($formNote->isSubmitted() && $formNote->isValid()) {
           
            //recuperer user + recette
            $note->setUserId($this->getUser());
            $note->setRecetteId($recette);          
            // dd($recette->getId() );
            $noteRepository->save($note, true);           

            return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId() ], Response::HTTP_SEE_OTHER);
         
        }    

        // on verifie si le user connecté a deja noté
        $noted = $noteRepository->findBy( array('user_id' => $this->getUser(), 'recette_id' => $recette ) );
        
        
        //on recupere toutes les notes, et on fait la moyenne
        $notes = $recette->getNotes();
        $SumNotes = 0;
        $moyNotes = 0;
        $count = 0;
        foreach($notes as $note) {
            $SumNotes += $note->getValeurNote();
            $count++;
        }
       

        if($count >0 ){
            $moyNotes = $SumNotes/$count;
        }
        // dd($moyNotes);
        
        
        return $this->renderForm('recette/show.html.twig', [
            'recette' => $recette,
            'form' => $form,
            'formNote' => $formNote,
            'commentaires' => $commentaires,
            'noted' => $noted,
            'nbrNote' => $count,
            'moyNotes' => $moyNotes
        ]);
    }





    #[Route('/{id}/edit', name: 'app_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette, RecetteRepository $recetteRepository): Response
    {
        
        
        
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recetteRepository->save($recette, true);

            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, RecetteRepository $recetteRepository): Response
    {
        
        
        
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $recetteRepository->remove($recette, true);
        }

        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
    }
}
