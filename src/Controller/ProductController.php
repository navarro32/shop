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
use App\Entity\Task;
use App\Entity\categorias;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductController extends Controller
{
    public function Index(Connection $connection)
    {		
		
		 $articles = $connection->fetchAll('SELECT product.id as id, categorias.nombre, product.nombre, product.descripcion, product.marca, product.precio FROM product, categorias WHERE product.categoria_id_id=categorias.id AND categorias.activo="1"');
		
		//$articles= $this->getDoctrine()->getRepository(Product::class)->findOneByIdJoinedToCategory();
			
      return $this->render('index.html.twig', array('products' => $articles));
		
		
    }
	
	 public function new_product(Connection $connection, Request $request)
    {	
		
		  // creates a task and gives it some dummy data for this example
        $task = new Task();
		 
		 $categorias = $connection->fetchAll('select * from categorias');

        $form = $this->createFormBuilder($task)
            ->add('codigo', TextType::class, array(
    								'attr' => array('class' => 'col s12', 'value'=>'')))			
			->add('categoria', EntityType::class, array(
				'class' => Categorias::class,
				'choice_label' => 'nombre',
			))			
           ->add('nombre', TextType::class, array(
    								'attr' => array('class' => 'col s12', 'value'=>'')))
			->add('descripcion', TextType::class, array(
    								'attr' => array('class' => 'col s12', 'value'=>'')))			
			->add('marca', TextType::class, array(
    								'attr' => array('class' => 'col s12', 'value'=>'')))
			->add('precio', TextType::class, array(
    								'attr' => array('class' => 'col s12', 'value'=>'')))
            ->add('Guardar', SubmitType::class,  array(
    								'attr' => array('class' => 'btn blue center-align col s12 m6 offset-m3', 'value'=>'')))
            ->getForm();
		 

		 	$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {			
				$task = $form->getData();
				
				$contador=$connection->fetchAll('select * from product where codigo="'.$task->getCodigo().'" or nombre = "'.$task->getNombre().'"');
				if(count($contador)>0){
					return $this->render('Product/new.html.twig', array(
						'form' => $form->createView(), 'categorias' => $categorias, 'error' => 'Ya existe un producto con este codigo o nombre'
					));
				}else{
					$query="INSERT INTO `product` (`id`, `categoria_id_id`, `codigo`, `nombre`, `descripcion`, `marca`, `precio`) VALUES (NULL, '".$task->getCategoria()->getId()."', '".$task->getCodigo()."', '".$task->getNombre()."', '".$task->getDescripcion()."', '".$task->getMarca()."', '".$task->getPrecio()."');";
					
					
					$insert=$connection->fetchAll($query);
					dd($insert); 
					return $this->redirectToRoute('Index');
				}
				
			}

		 
        return $this->render('Product/new.html.twig', array(
            'form' => $form->createView(), 'categorias' => $categorias
        ));
		 
		 
	 
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
