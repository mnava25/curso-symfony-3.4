<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;

class UserController extends Controller
{
	public function loginAction(Request $request){
		$authenticationsUtils = $this->get("security.authentication_utils");
		$error = $authenticationsUtils->getLastAuthenticationError();
		$lastUserName = $authenticationsUtils->getLastUsername();
		return $this->render(
					"@Blog/user/login.html.twig",array(
							"error" => $error,
							"last_username" => $lastUserName
					)
				);
	}
}
