<?php
include_once './Repository/BDD.php';
include_once './Models/planningModel.php';
include_once './exceptions.php';

class PlanningRepository {
    private $connection = null;

   
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
                $planningArray[$i]['lieux'],
                $planningArray[$i]['date_activite'],
                $planningArray[$i]['id_index'],
                $planningArray[$i]['id_activite']
            );
            $planning[$i]->setId($planningArray[$i]['id_planning']);

            $planning[$i]->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=".$planningArray[$i]['id_activite'])[0]);
        }
        return $planning;
    }

    //-------------------------------------

    public function getPlanningById($id){
        $planning = selectDB("PLANNINGS", "*", "id_planning='".$id."'")[0];
        
        $planningModel = new PlanningModel(
            $planning['id_planning'],
            $planning['description'],
            $planning['lieux'],
            $planning['date_activite'],
            $planning['id_index'],
            $planning['id_activite']
            
        );

        $planningModel->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=".$planning['id_activite'])[0]);
        
        return $planningModel;
    }

    //-------------------------------------
    
    public function createPlanning(PlanningModel $planning){

        $string = "lieux='" . $planning->lieux . "' AND date_activite='" . $planning->date_activite."'";

        $Select = selectDB("PLANNINGS", "*", $string, "bool");

        if($Select){
            exit_with_message("Y'a déjà une même activité", 403);
        }
       
        $create = insertDB("PLANNINGS", [ "description", "lieux", "date_activite", "id_index", "id_activite"], [
            $planning->description,
            $planning->lieux,
            $planning->date_activite,
            $planning->id_index,
            $planning->id_activite
        ]);

        if(!$create){
            exit_with_message("Error, the planning can't be created, plz try again", 500);
        }

        $planning->setId($id_planning);
        return $create;
    }

    //-------------------------------------
    
    public function updatePlanning(PlanningModel $planning) {
    $planningRepository = new PlanningRepository();
    $updated = $planningRepository->updateDB(
        "PLANNINGS",
        ["id_planning", "description", "lieux", "date_activite", "id_index", "id_activite"],
        [
            $planning->id_planning,
            $planning->description,
            $planning->lieux,
            $planning->date_activite,
            $planning->id_index,
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


    public function joinActivity($userId, $planningId) {

        $user = selectDB("UTILISATEUR", "*", "id_user=".$userId, "bool");

        $planning = selectDB("PLANNINGS", "*", "id_planning=".$planningId, "bool");

        if(!$user){
            exit_with_message("Cet utilisateur n'existe pas");
        }

        if($planning){
            exit_with_message("Ce planning n'existe pas");
        }

        $create = insertDB("PARTICIPE", [ "id_user", "id_planning"], [$userId, $planningId]);


        if ($create) {
            return true;
        } else {
            return false;
        }

    }

}
?>
