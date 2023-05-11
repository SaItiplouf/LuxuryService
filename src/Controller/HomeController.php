<?php

namespace App\Controller;

use App\Entity\Applications;
use App\Entity\JobCategory;
use App\Entity\Offers;
use App\Entity\Candidates;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private $entityManager;
    private $security;
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }






    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/company', name: 'app_company')]
    public function company(): Response
    {
        return $this->render('home/company.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/jobs-index', name: 'app_jobs_index')]
    public function jobIndex(): Response
    {
        $offers = $this->entityManager->getRepository(Offers::class)
            ->createQueryBuilder('offer')
            ->leftJoin('offer.jobCategory', 'job')
            ->leftJoin('offer.client', 'client')
            ->addSelect('job')
            ->addSelect('client')
            ->getQuery()
            ->getResult();
        // $offers2 = $this->entityManager->getRepository(Offers::class)->findAll();
        // dd($offers);
        return $this->render('home/jobs-index.html.twig', [
            'controller_name' => 'HomeController',
            'offers' => $offers,
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('home/login.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        $JobCategory = $this->entityManager->getRepository(JobCategory::class)->findAll();

        return $this->render('home/profile.html.twig', [
            'controller_name' => 'HomeController',
            'job_category' => $JobCategory,
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('home/register.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/show-index/{id}', name: 'app_show_index')]
    public function showIndex(Offers $offer): Response
    {
        $user = $this->security->getUser();
        // dd($user->getId());
        $candidat = $this->entityManager->getRepository(Candidates::class)->findOneBy([
            'userId' => $user->getId(),
        ]);

        $iscandidate = $this->entityManager->getRepository(Applications::class)->findOneBy([
            'candidate' => $candidat->getId(),
            'offer' => $offer->getId(),
        ]);
        $offers = $this->entityManager->getRepository(Offers::class)->findAll();

        return $this->render('home/show-index.html.twig', [
            'controller_name' => 'HomeController',
            'offer' => $offer,
            'offers' => $offers,
            'iscandidate' => $iscandidate
        ]);

    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(EntityManagerInterface $entityManager): Response
    {
        $JobCategory = $entityManager->getRepository(JobCategory::class)->findAll();

        return $this->render('home/admin.html.twig', [
            'controller_name' => 'HomeController',
            'job_category' => $JobCategory,
        ]);
    }
}