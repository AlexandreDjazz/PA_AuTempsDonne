<?php

include_once './Repository/stockRepository.php';
include_once './index.php';

class StockService {

    function isValidDate($date) {
        $pattern = '/^(\d{4})-(\d{2})-(\d{2})$/';
        if (preg_match($pattern, $date, $matches)) {
            $year = $matches[1];
            $month = $matches[2];
            $day = $matches[3];
            if (checkdate($month, $day, $year)) {
                return true;
            }
        }
        return false;
    }

//----------------------------------------------------------------------------------

    public function getAllStock($apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
            $stockRepository = new StockRepository();
            return $stockRepository->getAllStock();
        }else{
            exit_with_message("You didn't have access to this command");
        }

    }

//----------------------------------------------------------------------------------

    public function getAllStockInEntrepots($id, $apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
        $stockRepository = new StockRepository();
        return $stockRepository->getAllStockInEntrepots($id);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

//--------------------------------------------------------------------------------------------

    public function getAllProduitsInEntrepots($id,$produit, $apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
            $stockRepository = new StockRepository();
            return $stockRepository->getAllProduitsInEntrepots($id,$produit);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

//----------------------------------------------------------------------------------

    public function createStock(StockModel $stock,$id_entrepots, $apiKey)
    {
        $stockRepository = new StockRepository();

        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {



            $string = "id_entrepot=" .$id_entrepots;
            $etagere= selectDB("ETAGERES", "id_etagere,nombre_de_place",$string);


            if(!$etagere){
                exit_with_message("cette etagere n'existe pas ", 500);
            }

            $toutranger = 0;

            $tabb = [];

            for ($j = 0; $j < count($etagere); $j++) {


                // Regrde le nombre de place disponible dns l'étagère
                $nbProduitsEtagere = $this->getnbplace($etagere[$j]['id_etagere']);

                if ($nbProduitsEtagere < $etagere[$j]['nombre_de_place']) {

                    //la place dans l'etagere
                    $place = $etagere[$j]['nombre_de_place'] - $nbProduitsEtagere;

                    $qte = 0;
                    $toutranger = $toutranger + $stock->quantite_produit;

                    if($stock->quantite_produit > $place){
                        $toutranger = $toutranger + $place;
                        $qte = $place;
                    } else {
                        $toutranger = $toutranger + $stock->quantite_produit;
                        $qte = $stock->quantite_produit;
                    }


                    $qte = $stock->quantite_produit;
                    // Ranger des trucs
                    $stock->quantite_produit = $stock->quantite_produit - $qte;


//                    $qte=$stock->quantite_produit;
//
//                    $stock->quantite_produit=$stock->quantite_produit-$place;
//
//                    $arentre=$qte-$stock->quantite_produit;

                    var_dump($stock->quantite_produit);
                    echo "/";
                    var_dump($toutranger);

                    $tabb[$etagere[$j]['id_etagere']] = $arentre;
                    $tabb[$etagere[$j]['id_etagere']] = $qte;


                    if($stock->quantite_produit <= 0){
                        break;
                    }
                }

            }

            var_dump($tabb);
            exit();











            $string_prod = "id_produit=" .$stock->id_produit ;
            $produit= selectDB("PRODUIT", "id_produit",$string_prod,"bool");

            if(!$produit){
                exit_with_message("ce produit n'existe pas ", 500);
            }

            if (($stock->date_sortie == "NULL" && $stock->date_entree =="NULL")) {
                exit_with_message("Erreur : il faut rentre une date", 400);
            }

            if (($stock->date_sortie != "NULL" && $stock->date_entree !="NULL")) {
                exit_with_message("Erreur : il ne faut pas deux date", 400);
            }


            if (($stock->date_sortie == "NULL" && $stock->date_entree !="NULL")) {
                if (!$this->isValidDate($stock->date_entree)) {
                exit_with_message("Erreur : La date d'entrée est invalide. Le format attendu est AAAA-MM-JJ.", 403);
                }
                $sum=0;


               if($entrepots[0]["nombre_de_place"]<=$sum){
                  exit_with_message("il n'y a plus de place sur cette etagere");
               }elseif ($entrepots[0]["nombre_de_place"]-$sum<=$stock->quantite_produit) {
                   $new_quantite = $entrepots[0]["nombre_de_place"] - $sum;
                   $stock->quantite_produit = $new_quantite;
                   echo $stock->quantite_produit;

               }

                exit();
            }

            if (($stock->date_sortie != "NULL" && $stock->date_entree =="NULL")) {
                $sum = 0;
                if (!$this->isValidDate($stock->date_sortie)) {
                exit_with_message("Erreur : La date de sortie est invalide. Le format attendu est AAAA-MM-JJ.", 403);
                }

                $test = $this->getAllProduitsInEntrepots($stock->id_entrepot,$stock->id_produit,$apiKey);

                $id_produit_stock = array();
                foreach ($test as $item) {
                    $id_produit_stock[] = array(
                        'id_stock' => $item->id_stock,
                        'quantite_produit' => $item->quantite_produit,
                        'date_entree' => $item->date_entree,
                        'date_sortie' => $item->date_sortie

                    );
                    if ($item->date_entree != null){
                        $sum= $sum +$item->quantite_produit;
                    }else{
                        $sum=$sum-$item->quantite_produit;
                    }
                }

               if ($sum-$stock->quantite_produit <=0){
                   exit_with_message(" il n'y a pas acces de stock de ce produit dans cette entrepots");
               }

            }

            if ($stock->date_peremption == "NUlL"){
                return $stockRepository->createStock($stock);
             } else if (!$this->isValidDate($stock->date_peremption)) {
                exit_with_message("Erreur : La date de péremption est invalide. Le format attendu est AAAA-MM-JJ.", 403);
            }

            return $stockRepository->createStock($stock);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

    public function deleteStock($id,$apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
            $stockRepository = new StockRepository();
            return $stockRepository->deleteStock($id);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

    private function getnbplace($id)
    {
        $string = "id_etagere=".$id;
        $number= selectDB("STOCKS","quantite_produit",$string,"bool");
        for ($i = 0; $i < count($number); $i++) {
            $sum=$sum+$number[$i]["quantite_produit"];
        }

        return $sum;
    }
}
?>