<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Curso;
use AppBundle\Form\CursoType;
use Symfony\Component\Validator\Constraints as Assert;

class PruebasController extends Controller
{

    public function indexAction(Request $request,$name,$page)
    {
        
        //return $this->redirect($this->generateUrl("helloWorld"));
        //return $this->redirect($request->getBaseUrl()."/hello-world?hola=true");
        $productos = array(
            array("producto" => "Consola","Precio"=>2),
            array("producto" => "Consola 2","Precio"=>3),
            array("producto" => "Consola 3","Precio"=>4),
            array("producto" => "Consola 4","Precio"=>5),
            array("producto" => "Consola 5","Precio"=>6)
        );
        $frutas = array("manzana"=>"golden","pera"=>"rica");
        return $this->render('@App/pruebas/index.html.twig', [
            "texto" => $name." - ".$page,
            "productos" => $productos,
            "frutas"=>$frutas
        ]);
    }
    
    public function createAction() {
        $curso = new Curso();
        $curso->setTitulo("Curso de Symfony 3");
        $curso->setDescripcion("Curso de Symfony 3 completo");
        $curso->setPrecio(80.5);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($curso);
        $flush = $em->flush();
        if ($flush != null) {
            echo "El curso no se ha creado correctamente";
        }else {
            echo "El curso se ha creado correctamente";
        }
        die();
    }
    
    public function readAction() {
        $em = $this->getDoctrine()->getManager();
        $curso_rep = $em->getRepository("AppBundle:Curso");
        $cursos = $curso_rep->findAll();
        
        foreach ($cursos as $curso) {
            echo $curso->getTitulo()." - ".$curso->getDescripcion()." - ".$curso->getPrecio()."<br/><hr>";
        }
        
        die();
    }
    
    public function updateAction($id,$titulo,$descripcion,$precio) {
        $em = $this->getDoctrine()->getManager();
        $curso_rep = $em->getRepository("AppBundle:Curso");
        $curso = $curso_rep->find($id);
        $curso->setTitulo($titulo);
        $curso->setDescripcion($descripcion);
        $curso->setPrecio($precio);
        $em->persist($curso);
        $flush = $em->flush();
        if ($flush =! null) {
            echo "El curso no se ha actualizado correctamente";
        }else {
            echo "El curso se ha actualizado correctamente";
        }
        //4591594015
        die();
    }
    
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $curso_rep = $em->getRepository("AppBundle:Curso");
        $curso = $curso_rep->find($id);
        $em->remove($curso);
        $flush = $em->flush();
        if ($flush != null) {
            echo "El curso no se ha eliminado correctamnte";
        }
        else {
            echo "El curso se ha eliminado correctamnte";
        }
        
        die();
    }
    
    public function nativeSqlAction() {
        $em = $this->getDoctrine()->getManager();
        $curso_rep = $em->getRepository("AppBundle:Curso");
        /*$query = $em->createQuery("
                    SELECT c FROM AppBundle:Curso c
                    WHERE c.precio > :precio
                ")->setParameter("precio","80");
        $cursos = $query->getResult();
        /*$db = $em->getConnection();
        $query = "SELECT * FROM cursos";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $cursos = $stmt->fetchAll();*/
        
        /*$query = $curso_rep->createQueryBuilder("c")
                ->where("c.precio > :precio")
                ->setParameter("precio","90")
                ->getQuery();
        $cursos = $query->getResult();*/
        $cursos = $curso_rep->getCursos();
        foreach ($cursos as $curso) {
            echo $curso->getTitulo()." - ".$curso->getDescripcion()." - ".$curso->getPrecio()."<br/><hr>";
        }
        
        die();
    }
    
    public function formAction(Request $request) {
        $curso = new Curso();
        $form = $this->createForm(CursoType::class,$curso);
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            $status = "Formulario valido";
            $data = array(
                "titulo" => $form->get("titulo")->getData(),
                "descripcion" => $form->get("descripcion")->getData(),
                "precio" => $form->get("precio")->getData()
            );
        }
        else {
            $status = null;
            $data = null;
        }
        return $this->render('@App/pruebas/form.html.twig', [
            "form" => $form->createView(),
            "status" => $status,
            "data" => $data
        ]);
    }
    
    public function validarEmailAction($email) {
        $emailConstraint = new Assert\Email();
        $emailConstraint->message = "Pasame un buen correo";
        $error = $this->get("validator")->validate($email,$emailConstraint);
        if (count($error) == 0) {
            echo "<h1>Correo Valido!!!</h1>";
        }else {
            echo $error[0]->getMessage();
        }
        die();
    }
}