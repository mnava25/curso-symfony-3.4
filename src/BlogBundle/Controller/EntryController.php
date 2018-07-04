<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Entry;
use BlogBundle\Form\EntryType;
use Symfony\Component\HttpFoundation\Session\Session;

class EntryController extends Controller
{
    private $session;
    public function __construct(){
    	$this->session = new Session();
    }
	public function indexAction()
    {
        return $this->render('BlogBundle:Entry:index.html.twig', array(
            // ...
        ));
    }

    public function addAction(Request $request=null)
    {
        $entry = new Entry();
        $form = $this->createForm(EntryType::class,$entry);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
        	if ($form->isValid()) {
        		$em = $this->getDoctrine()->getManager();
        		$category_repo = $em->getRepository("BlogBundle:Category");
        		$entry->setTitle($form->get("title")->getData());
        		$entry->setContent($form->get("content")->getData());
        		$entry->setStatus($form->get("status")->getData());
        		$file = $form["image"]->getData();
        		$ext = $file->guessExtension();
        		$file_name = time().".".$ext;
        		$file->move("uploads",$file_name);
        		$entry->setImage($file_name);
        		$category = $category_repo->find($form->get("category")->getData());
        		$entry->setCategory($category);
        		$user = $this->getUser();
        		$entry->setUser($user);
        		$em->persist($entry);
        		$flush = $em->flush();
        		if ($flush == null) {
        			$status = "La entrada se creo correctamente";
        		}else {
        			$status = "La entrada no se creo correctamente";
        		}
        		
        	}
        	else {
        		$status = "La entrada no se pudo crear, porque el formulario es invalido";
        	}
        	$this->session->getFlashBag()->add("status", $status);
        }
    	return $this->render('@Blog/Entry/add.html.twig', array(
            "form"=>$form->createView()
        ));
    }

}
