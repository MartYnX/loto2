<?php

namespace App\Controller;

use App\Repository\ResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController {
    /**
     * @Route("/api/ai-je-gagner", name="api_ai_je_gagner", methods={"GET"})
     */
    public function aiJeGagner(Request $request, ResultRepository $resultRepository) {
        // Récupération de la grille jouée depuis les paramètres de la requête
        $grille = $request->query->get('grille');

        // Séparation des numéros joués et du numéro chance
        list($numeros, $chance) = explode('|', $grille);

        // Conversion des numéros joués en tableau
        $numeros = explode(',', $numeros);
        
        // Récupération du dernier tirage enregistré dans la base de données
        $dernierResultat = $resultRepository->findOneBy([], ['date' => 'DESC']);

        // Vérification si l'utilisateur a gagné
        $gagne = in_array($dernierResultat->getNumeroChance(), $numeros) || count(array_intersect($numeros, [
            $dernierResultat->getNumero1(),
            $dernierResultat->getNumero2(),
            $dernierResultat->getNumero3(),
            $dernierResultat->getNumero4(),
            $dernierResultat->getNumero5()
        ])) >= 2;
        
        return $this->json(['gagne' => $gagne]);
    }
}