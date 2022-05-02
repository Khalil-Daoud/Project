<?php

namespace App\Controller;
use App\Entity\Crud;
use App\Form\CrudType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{


    /**
     * @Route("/", name="app_main")
     */
    public function index(): Response
    {
        $data = $this->getDoctrine()->getRepository(Crud::class)->findAll();
        return $this->render('main/index.html.twig', [
          'list'=>$data
        ]);
    }


    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
       $crud = new Crud();
       $form = $this->createForm(CrudType::Class,$crud);
       $form->handleRequest($request);
        
       if($form->isSubmitted() && $form->isvalid())
       {
       $em = $this->getDoctrine()->getManager();
       $em->persist($crud);             //sauvgarder les données dans la base de données
       $em->flush();                    //confirmée la suvgarde des données
       $this->addFlash('notice','Submitted Successfully');

       //thezek l page okhra
       return $this->redirectToRoute('app_main');

   }
       return $this->render('main/create.html.twig',['form' => $form->createView()]);
    }


     /**
     * @Route("/create", name="ajouter")
     */
    public function ajouter(){
        return $this->redirectToRoute('create');
    }

    /**
     * @Route("/update/{id}", name="update")
     */

    public function update(Request $request, $id){
        $data = $this->getDoctrine()->getRepository(Crud::class)->find($id);
        $form = $this->createForm(CrudType::Class,$data);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isvalid())
        {
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        $this->addFlash('notice','Updated Successfully');

        //thezek l page okhra
        return $this->redirectToRoute('app_main');

        }
        return $this->render('main/update.html.twig',['form' => $form->createView()]);
}


    
   /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request , $id){
        $data = $this->getDoctrine()->getRepository(Crud::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();


        $this->addFlash('notice','Deleted Successfully');

        return $this->redirectToRoute('app_main');

    }




}
