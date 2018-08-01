<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Product;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Doctrine\DBAL\Driver\Connection;


class ProductController extends Controller
{
    public function Index(Connection $connection)
    {		
		
		 $articles = $connection->fetchAll('SELECT product.id as id, categorias.nombre, product.nombre, product.descripcion, product.marca, product.precio FROM product, categorias WHERE product.categoria_id_id=categorias.id AND categorias.activo="1"');
		
		//$articles= $this->getDoctrine()->getRepository(Product::class)->findOneByIdJoinedToCategory();
			
      return $this->render('index.html.twig', array('products' => $articles));
		
		
    }
	
	 public function new_product()
    {	
	 return $this->render('Product/new.html.twig');
	 }
	

	
	
	
public function show($currentPage = 1)
    {
		
	  $em = $this->getDoctrine()->getManager();
	  $limit = 1;
	  $producto = $em->getRepository(Product::class)->getAllPers($currentPage, $limit);
	  $productoResultado = $producto['paginator'];
	  $productoQueryCompleta =  $producto['query'];
		
	  $maxPages = ceil($producto['paginator']->count() / $limit);
	
		$articles= $this->getDoctrine()->getRepository(Product::class)->findAll();
	  return $this->render('lista.html.twig', array(
			'producto' => $productoResultado,
			'maxPages'=>$maxPages,
			'thisPage' => $currentPage,
			'all_items' => $productoQueryCompleta
		) );
	
	
	
	
	
		
    }
	
	 public function single($id) {
      $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
      return $this->render('Product/show.html.twig', array('product' => $product));
    }
	
	
}