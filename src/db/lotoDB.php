<?php

namespace App\db;

use App\Entity\Result;
use Doctrine\ORM\EntityManagerInterface;

class LotoService {
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    // Fonction pour importer les résultats depuis un fichier CSV
    public function importResultsFromCsv($csvFilePath) {
        if (($handle = fopen($csvFilePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $result = new Result();
                $result->setDate(new \DateTime($data[0]));
                $result->setNumbers($data[1]);
                $result->setBonus($data[2]);
                $this->em->persist($result);
            }
            fclose($handle);
            $this->em->flush();
        }
    }
}