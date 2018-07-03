<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Category;
use BlogBundle\Form\CategoryType;

class CategoryController extends Controller
{
	private $session;
	public function __construct() {
		$this->session = new Session();
	}
	public function indexAction(){
		$em = $this->getDoctrine()->getManager();
		$category_repo = $em->getRepository("BlogBundle:Category");
		$categories = $category_repo->findAll();
		return $this->render("@Blog/Category/index.html.twig",array(
				"categories"=>$categories
		));
	}
	public function addAction(Request $requet = null){
		$category = new Category();
		$form = $this->createForm(CategoryType::class,$category);
		$form->handleRequest($requet);
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$category->setName($form->get("name")->getData());
				$category->setDescription($form->get("description")->getData());
				$em->persist($category);
				$flush = $em->flush();
				if ($flush == null) {
					$status ="La categoria se ha creado correctamente";
				}else {
					$status = utf8_encode("Error al añadir la categoria");
				}				
			}else{
				$status = "La categoria no ha sido creado porque el formulario es invalido";
			}
			$this->session->getFlashBag()->add("status",$status);
			return $this->redirectToRoute("blog_index_category");
		}
		return $this->render("@Blog/Category/add.html.twig",array(
				"form"=>$form->createView()
		));
	}
	public function deleteAction($id){
		$em = $this->getDoctrine()->getManager();
		$category_repo = $em->getRepository("BlogBundle:Category");
		$category = $category_repo->find($id);
		if (count($category->getEntries()) == 0) {
			$em->remove($category);
			$em->flush();
		}	
		return $this->redirectToRoute("blog_index_category");
	}
	public function editAction(Request $requet = null,$id){
		$em = $this->getDoctrine()->getManager();
		$category_repo= $em->getRepository("BlogBundle:Category");
		$category = $category_repo->find($id);
		$form = $this->createForm(CategoryType::class,$category);
		$form->handleRequest($requet);
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$category->setName($form->get("name")->getData());
				$category->setDescription($form->get("description")->getData());
				$em->persist($category);
				$flush = $em->flush();
				if ($flush == null) {
					$status ="La categoria se ha editado correctamente";
				}else {
					$status = utf8_encode("Error al editar la categoria");
				}
			}else{
				$status = "La categoria no ha editado porque el formulario es invalido";
			}
			$this->session->getFlashBag()->add("status",$status);
			return $this->redirectToRoute("blog_index_category");
		}
		return $this->render("@Blog/Category/edit.html.twig",array(
				"form"=>$form->createView()
		));
	}
}
