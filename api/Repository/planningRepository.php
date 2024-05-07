<?php
include_once './Repository/BDD.php';
include_once './Models/planningModel.php';
include_once './exceptions.php';
include_once './index.php';

class PlanningRepository {

    function __construct() {
       
    }
    
    //-------------------------------------

    public function getAllPlanning(){
        $planningArray = selectDB("PLANNINGS", "*");

        $planning = [];

        for ($i=0; $i < count($planningArray); $i++) {
            $planning[$i] = new PlanningModel(
                $planningArray[$i]['id_planning'],
                $planningArray[$i]['description'],
                $planningArray[$i]['date_activite'],
                $planningArray[$i]['id_index_planning'],
                $planningArray[$i]['id_activite']
            );
            $planning[$i]->setId($planningArray[$i]['id_planning']);

            $planning[$i]->setIndexPlanning(selectDB("INDEXPLANNING", "index_nom_planning", "id_index_planning=".$planningArray[$i]['id_index_planning'])[0]);
            $planning[$i]->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=".$planningArray[$i]['id_activite'])[0]);
        }
        return $planning;
    }

    //-------------------------------------

        public function getPlanningByUser($apiKey)
    {
        $id = getIdUserFromApiKey($apiKey);
        $id_planning = selectDB("PARTICIPE", "id_planning", "id_user='" . $id . "'");

        $allPlanning = [];

        foreach ($id_planning as $planning_id) {
            $planningArray = selectDB("PLANNINGS", "*", "id_planning='" . $planning_id[0] . "'");

            foreach ($planningArray as $planningData) {
                $planning = new PlanningModel(
                    $planningData['id_planning'],
                    $planningData['description'],
                    $planningData['date_activite'],
                    $planningData['id_index_planning'],
                    $planningData['id_activite']
                );
                $planning->setId($planningData['id_planning']);
                $planning->setIndexPlanning(selectDB("INDEXPLANNING", "index_nom_planning", "id_index_planning=" . $planningData['id_index_planning'])[0]);
                $planning->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=" . $planningData['id_activite'])[0]);

                $allPlanning[] = $planning;
            }
        }

        return $allPlanning;
    }


    //-------------------------------------
    
    public function createPlanning(PlanningModel $planning){


        $create = insertDB("PLANNINGS", [ "description", "date_activite", "id_index_planning", "id_activite"], [
            $planning->description,
            $planning->date_activite,
            $planning->id_index_planning,
            $planning->id_activite
        ]);

        if(!$create){
            exit_with_message("Error, the planning can't be created, plz try again", 500);
        }

        return $create;
    }

    //-------------------------------------
    
    public function updatePlanning(PlanningModel $planning) {
    $updated = updateDB(
        "PLANNINGS",
        ["id_planning", "description", "date_activite", "id_index_planning", "id_activite"],
        [
            $planning->id_planning,
            $planning->description,
            $planning->date_activite,
            $planning->id_index_planning,
            $planning->id_activite
        ],
        "id_planning=" . $planning->id_planning
    );

    if (!$updated) {
        exit_with_message("Erreur, le planning n'a pas pu être mis à jour. Veuillez réessayer.", 500);
    }

    return $planning;
}


    //-------------------------------------

    public function deletePlanning($id){
        $deleted = deleteDB("PLANNINGS", "id_planning=".$id);

        if(!$deleted){
            exit_with_message("Error, the planning can't be deleted, plz try again", 500);
        }

        return true;
    }

     //-------------------------------------


    public function joinActivity($userId, $planningId, $confirme) {

        $user = selectDB("UTILISATEUR", "*", "id_user=".$userId, "bool");

        $planning = selectDB("PLANNINGS", "*", "id_planning=".$planningId, "bool");

        if(!$user){
            exit_with_message("Cet utilisateur n'existe pas");
        }

        if(!$planning){
            exit_with_message("Ce planning n'existe pas");
        }

        $create = insertDB("PARTICIPE", [ "id_user", "id_planning","confirme"], [$userId, $planningId ,$confirme]);


        if ($create) {
            return true;
        } else {
            return false;
        }

    }

}
?>