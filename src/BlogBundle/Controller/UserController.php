<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BlogBundle\Entity\User;
use BlogBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
	private $session;
	public function __construct(){
		$this->session = new Session();		
	}
	public function loginAction(Request $request=null){
		$authenticationsUtils = $this->get("security.authentication_utils");
		$error = $authenticationsUtils->getLastAuthenticationError();
		$lastUserName = $authenticationsUtils->getLastUsername();
		
		$user = new User();
		$form = $this->createForm(UserType::class,$user);
		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			if ($form->isValid()) {	
				$em = $this->getDoctrine()->getManager();
				$user_repo = $em->getRepository("BlogBundle:User");
				$user_count = $user_repo->findOneBy(array("email"=>$form->get("email")->getData())); 
				if (count($user_count) == 0) {
					$user->setName($form->get("name")->getData());
					$user->setSurname($form->get("surname")->getData());
					$user->setEmail($form->get("email")->getData());
					
					$factory = $this->get("security.encoder_factory");
					$encoder = $factory->getEncoder($user);
					$password = $encoder->encodePassword($form->get("password")->getData(),$user->getSalt());
					$user->setPassword($password);
					$user->setRole("ROLE_USER");
					$user->setImagen(null);
					$em = $this->getDoctrine()->getManager();
					$em->persist($user);
					$flush = $em->flush();
					if ($flush == null) {
						$status = "El usuario se ha creado correctamente";
					}else {
						$status = "No te has registrado correctamente";;
					}
				}else {
					$status = "El usuario ya existe!!";
				}				
			}
			else {
				$status = "No te has registrado correctamente";
			}
			
			$this->session->getFlashBag()->add("status", $status);
		}
		return $this->render(
					"@Blog/User/login.html.twig",array(
							"error" 		=> $error,
							"last_username" => $lastUserName,
							"form" 			=> $form->createView()
					)
				);
	}
}
