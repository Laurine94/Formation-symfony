<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller{
    
    /**
     * @Route("/", name="homepage")
     *
     */
    public function home(AdRepository $adRepo, UtilisateurRepository $userRepo){

        return $this->render(
            'home.html.twig',[
                'ads'=>$adRepo->findBestAds(3),
                'users'=>$userRepo->findBestUsers(2)
            ]
            
        );
    }
}


?>