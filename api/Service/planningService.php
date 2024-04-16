<?php
include_once './Repository/planningRepository.php'; 
include_once './Models/planningModel.php';

class PlanningService {
    
    /*
     *  Récupère tous les planning
    */
    public function getAllPlanning() {
        $planningRepository = new PlanningRepository();
        return $planningRepository->getAllPlanning();
    }

    /*
     *  Récupère un planning par son id
    */
    public function getPlanningById($id) {
        $planningRepository = new PlanningRepository();
        return $planningRepository->getPlanningById($id);
    }

    /*
     *  Créer un planning
    */
    public function createPlanning(PlanningModel $planning) {
        $planningRepository = new PlanningRepository();
        return $planningRepository->createPlanning($planning);
    }

    /*
     *  Met à jour un planning
    */
    public function updatePlanning( $id_planning, $description, $lieux, $date_activite, $id_index, $id_activite) {
        $planningRepository = new PlanningRepository();
        $updatedPlanning = new PlanningModel(
            $id_planning,
            $description,
            $lieux,
            $date_activite,
            $id_index,
            $id_activite
        );
        $updatedPlanning->setId($id);
        return $planningRepository->updatePlanning($updatedPlanning);
    }

    /*
     *  Supprime un planning
    */
    public function deletePlanning($id) {
        $planningRepository = new PlanningRepository();
        return $planningRepository->deletePlanning($id);
    }

    /*
     *  rejoind une activiter
    */
    public function joinActivity($userId, $planningId) {
    $planningRepository = new PlanningRepository();
    return $planningRepository->joinActivity($userId, $planningId);
}

}
?>