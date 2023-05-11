<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Candidates;
use App\Entity\Applications;
use App\Entity\JobCategory;
use App\Entity\Offers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;

class CandidatesController extends AbstractController
{
    private $entityManager;
    private $security;
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    #[Route('/apply/{id}', name: 'apply_for_offer')]
    public function applyForOffer(Request $request, EntityManagerInterface $entityManager, Offers $offer): Response
    {
        // Récupérer l'utilisateur connecté depuis la session
        $user = $this->security->getUser();
        $candidate = $this->entityManager->getRepository(Candidates::class)->findOneBy(['userId' => $user]);

        if (!$user) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

        if (!$offer) {
            // Rediriger l'utilisateur vers une page d'erreur s'il n'y a pas d'offre avec l'ID donné
            throw $this->createNotFoundException('No offer found for id');
        }


        // Vérifier si le candidat a déjà postulé à cette offre
        $existingCandidature = $entityManager->getRepository(Applications::class)->findOneBy([
            'candidate' => $candidate,
            'offer' => $offer,
        ]);

        if ($existingCandidature) {


            // Ajouter un message flash indiquant que le candidat a déjà postulé
            $this->addFlash('warning', 'You have already applied for this job.');

            // Rediriger l'utilisateur vers la page de l'offre
            return $this->redirectToRoute('app_show_index', ['id' => $offer->getId()]);


        } else {
            // Créer une nouvelle candidature et l'ajouter à la base de données
            $candidature = new Applications();
            $candidature->setOffer($offer);
            $candidature->setCandidate($candidate);
            $entityManager->persist($candidature);
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page de confirmation
            return $this->redirectToRoute('app_show_index', ['id' => $offer->getId()]);
        }
    }

    #[Route('/candidates', name: 'app_candidates_save')]
    public function save(Request $request, EntityManagerInterface $entityManager, Security $security, UserRepository $userRepository): Response
    {
        $user = $security->getUser();

        // Get the existing candidate for the user, if it exists
        $candidate = $userRepository->findOneBy(['user' => $user]);

        // If the candidate doesn't exist, create a new one
        if (!$candidate) {
            $candidates = new Candidates();
        }


        $data = $request->request->all();
        $jobcategory = $entityManager->getRepository(JobCategory::class)->find($data["job_sector"]);




        $candidates->setFirstname($data["first_name"])
            ->setLastname($data["last_name"])
            ->setCurrentLocation($data["current_location"])
            ->setAddress($data["address"])
            ->setCountry($data["country"])
            ->setNationality($data["nationality"])
            ->setBirthLocation($data["birth_place"])
            ->setExperience($data["experience"])
            ->setShortDescription($data["description"])
            ->setGender($data["gender"])
            ->setUser($user)
            ->setJobCategory($jobcategory);
        // ->setBirthdate($data["birth_date"]);

        $entityManager->persist($candidates);
        $entityManager->flush();

        return $this->redirectToRoute('app_profile');
    }

}