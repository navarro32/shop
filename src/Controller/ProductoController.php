<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductoController extends Controller
{
    public function Index()
    {
        $number = random_int(0, 100);

      return $this->render('base.html.twig', array(
			'variable_name' => 'variable_value',
		));
    }
}