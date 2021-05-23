<?php


namespace fjerbi\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use fjerbi\AdminBundle\Entity\Product;
use fjerbi\AdminBundle\Entity\Post;
use fjerbi\AdminBundle\Event\PageViewEvent;
use fjerbi\AdminBundle\Event\PostViewEvent;
use fjerbi\AdminBundle\Form\PostType;
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
}
