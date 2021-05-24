<?php


namespace fjerbi\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use fjerbi\AdminBundle\Entity\Order;
use fjerbi\AdminBundle\Entity\Product;
use fjerbi\AdminBundle\Entity\Post;
use fjerbi\AdminBundle\Event\PageViewEvent;
use fjerbi\AdminBundle\Event\PostViewEvent;
use fjerbi\AdminBundle\Form\OrderType;
use fjerbi\AdminBundle\Form\PostType;
use fjerbi\AdminBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends AbstractController
{

    /**
     * @Route("/dashboard", name="admin_dashboard_home")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function indexAction(Request $request, EventDispatcherInterface $eventDispatcher = null)
    {
        $page = $request->query->get('page', 1);
        $em = $this->getDoctrine()->getManager();
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy(["isPublished" => true], ["created" => "desc"]);

        if ($eventDispatcher) {
            $eventDispatcher->dispatch(new PageViewEvent($page));
        }

        $hasNext = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();
        if ($request -> isMethod('post')){
            $search=$request->get('search');
            $posts = $em->getRepository(Post::class)
                ->findByTitle($search);
            return $this->render('@Admin/admin/dashboard.html.twig', array('posts' => $posts));
        }
        return $this->render('@Admin/admin/dashboard.html.twig', [
            'posts' => $posts
        ]);
    }


        //========================================products section ========================================
    /**
     * @Route("/manage/products", name="admin_dashboard_products")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function ManageProductsAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();
        return $this->render('@Admin/admin/product/manage.html.twig', [
            'products' => $products
        ]);
    }
    /**
     * @Route("/delete/product/{id}", name="admin_dashboard_delete_product")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function deleteFromCartAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $Product = $em
            ->getRepository(Product::class)
            ->find($id);
        $em->remove($Product);
        $em->flush();
        return $this->redirectToRoute('admin_dashboard_products');
    }

    /**
     * @Route("/create/products", name="admin_dashboard_create_products")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function CreateProductsAction(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $product->setViews(0);
            $product->setIsPublished(false);
            $product->setCreated(new \DateTime('now'));
            $product->setUpdated(new \DateTime('now'));
            $em->persist($product);
            $em->flush();
            $this->addFlash('info', 'Created Successfully !');
            // if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            //  return new RedirectResponse('/');
        }
        return $this->render('@Admin/admin/product/create.html.twig',array(
            "Form" => $form->createView()
        ));

    }
    /**
     * @Route("/edit/products/{id}", name="admin_dashboard_edit_product")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function UpdateProductAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('admin_dashboard_products');
        }
        return $this->render('@Admin/admin/product/edit.html.twig',
            array('form'=>$form->createView(),
                "product"=>$product));
    }

    //========================================Orders section ========================================
    /**
     * @Route("/manage/orders", name="admin_dashboard_orders")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function ManageOrdersAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findAll();
        return $this->render('@Admin/admin/order/manage.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/edit/order/{id}", name="admin_dashboard_edit_order")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function UpdateOrderAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($id);
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
            return $this->redirectToRoute('admin_dashboard_orders');
        }
        return $this->render('@Admin/admin/order/edit.html.twig',
            array('form'=>$form->createView(),
                "order"=>$order));
    }
}
