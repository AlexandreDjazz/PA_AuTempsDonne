<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class VehiculeRepository
{

    public function getAllVehicule()
    {
        $vehicule = selectDB("VEHICULES", "*");
        if(!$vehicule){
            exit_with_message("Impossible to select data for entrepot in the DB");
        }

        $vehiculetArray = [];

        for ($i=0; $i < count($vehicule) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($vehicule, $i);
            //$vehiculetArray[$i] = new VehiculeModel($vehicule[$i]["id_vehicule"], $vehicule[$i]["capacite"], $vehicule[$i]["nom_du_vehicules"], $vehicule[$i]["nombre_de_place"],$vehicule[$i]["id_entrepot"]);
        }

        exit_with_content($vehiculetArray);
    }
//----------------------------------------------------------------------------------
    public function getVehiculeById($int)
    {
        $dateDuJour = date('Y-m-d 00:00:00');

        $vehicule = selectDB("VEHICULES", "*", "id_vehicule = ".$int);
        if(!$vehicule){
            exit_with_message("Impossible to select data for entrepot in the DB");
        }

        $vehiculetArray = [];

        for ($i=0; $i < count($vehicule) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($vehicule, $i);
        }

        exit_with_content($vehiculetArray);
    }

    //----------------------------------------------------------------------------------

    public function getVehiculeAvailable($debut, $fin){
        $columns = "v.*";
        $join = "LEFT JOIN SERVICE s ON v.id_service = s.id_service";
        $condition = "s.id_service IS NULL 
                        OR NOT (
                            s.service_date_debut BETWEEN '".$debut." 00:00:00' AND '".$fin." 23:59:59'
                        OR s.service_date_fin BETWEEN '".$debut." 00:00:00' AND '".$fin." 23:59:59'
                        OR (s.service_date_debut < '".$debut." 00:00:00' AND s.service_date_fin > '".$fin." 23:59:59')
                        );";
        $data = selectJoinDB("VEHICULES v", $columns, $join, $condition);

        $vehiculetArray = [];
        for ($i=0; $i < count($data) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($data, $i);
        }

        exit_with_content($vehiculetArray);
    }
    //----------------------------------------------------------------------------------

    public function getMyVehiculeAvailable($debut, $fin, $id_user)
    {
        $columns = "v.*";
        $join = "LEFT JOIN SERVICE s ON v.id_service = s.id_service";
        $condition = "v.id_user= ".$id_user." AND s.id_service IS NULL 
                        OR NOT (
                            s.service_date_debut BETWEEN '".$debut." 00:00:00' AND '".$fin." 23:59:59'
                        OR s.service_date_fin BETWEEN '".$debut." 00:00:00' AND '".$fin." 23:59:59'
                        OR (s.service_date_debut < '".$debut." 00:00:00' AND s.service_date_fin > '".$fin." 23:59:59')
                        );";
        $data = selectJoinDB("VEHICULES v", $columns, $join, $condition);

        $vehiculetArray = [];
        for ($i=0; $i < count($data) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($data, $i);
        }

        exit_with_content($vehiculetArray);
    }

    //----------------------------------------------------------------------------------
    public function getMyVehicules($idUser){
        $data = selectDB("VEHICULES", "*", "id_user= ".$idUser);

        $vehiculetArray = [];
        for ($i=0; $i < count($data) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($data, $i);
        }

        exit_with_content($vehiculetArray);
    }

    //----------------------------------------------------------------------------------
    // User with the role 4
    public function getMyBookedVehicle($apiKey){
        $userId = getIdUserFromApiKey($apiKey);
        $columns = "s.*, v.*, u.id_user, u.telephone, u.email, u.id_role";
        $join = "LEFT JOIN SERVICE s ON lsv.id_service = s.id_service LEFT JOIN VEHICULES v ON lsv.id_vehicule = v.id_vehicule LEFT JOIN UTILISATEUR u ON s.id_user_booking = u.id_user";
        $condition = "s.id_user_booking=".$userId;
        $data = selectJoinDB("LINKSERVICEVEHICLE lsv", $columns, $join, $condition);
        $this->returnDesDataForBookedBookingVehicle($data);
    }

    //----------------------------------------------------------------------------------

    // User with the role 3, and had created a vehicle
    public function getMyVehicleWichAreBooked($apiKey){
        $userId = getIdUserFromApiKey($apiKey);
        $columns = "s.*, v.*, u.id_user, u.telephone, u.email, u.id_role";
        $join = "LEFT JOIN SERVICE s ON lsv.id_service = s.id_service LEFT JOIN VEHICULES v ON lsv.id_vehicule = v.id_vehicule LEFT JOIN UTILISATEUR u ON s.id_user_booking = u.id_user";
        $condition = "v.id_owner=".$userId;
        $data = selectJoinDB("LINKSERVICEVEHICLE lsv", $columns, $join, $condition);
        $this->returnDesDataForBookedBookingVehicle($data);

    }

    //----------------------------------------------------------------------------------

    public function getAllBookedVehicle(){
        $columns = "s.*, v.*, u.id_user, u.telephone, u.email, u.id_role";
        $join = "LEFT JOIN SERVICE s ON lsv.id_service = s.id_service LEFT JOIN VEHICULES v ON lsv.id_vehicule = v.id_vehicule LEFT JOIN UTILISATEUR u ON s.id_user_booking = u.id_user";
        $data = selectJoinDB("LINKSERVICEVEHICLE lsv", $columns, $join);
        $this->returnDesDataForBookedBookingVehicle($data);
    }

    //----------------------------------------------------------------------------------
    public function createVehicule(VehiculeModel $vehicule, $id_user)
    {
        $string = "immatriculation='" . $vehicule->immatriculation ."'";

        $Select = selectDB("VEHICULES", "*", $string, "bool");

        if($Select){
            exit_with_message("Y'a déjà un même vehicule", 403);
        }

        $create = insertDB("VEHICULES", ["capacite","nom_du_vehicules","nombre_de_place","id_entrepot", "immatriculation", "appartenance", "id_user"]
            ,[$vehicule->capacite ,$vehicule->nom_du_vehicules,$vehicule->nombre_de_place,$vehicule->id_entrepot, $vehicule->immatriculation, $vehicule->appartenance, $id_user]);

        if(!$create){
            exit_with_message("Error, the vehicule can't be created, plz try again", 500);
        }

        exit_with_message("Vehicule created", 200);
    }
    //------------------------------------------------------------------------------------------
    public function deleteVehicule($id)
    {
        $deleted = deleteDB("VEHICULES", "id_vehicule=".$id ,"bool");

        if(!$deleted){
            exit_with_message("Error, the activite can't be deleted, plz try again", 500);
        }
        exit_with_message("Vehicule deleted", 200);
    }

    //------------------------------------------------------------------------------------------

    public function bookingAVehicle($apiKey){

        $id_user = getIdUserFromApiKey($apiKey);
        $role = getRoleFromApiKey($apiKey);
        //insertDB("SERVICE", ["description_service", "type_service", "service_date_debut", "service_date_fin", "id_user_booking"], ["Partage de vehicule", 1, "", "", $id_user]);
    }

    //------------------------------------------------------------------------------------------

    public function unBookingAVehicle($id_service, $apiKey){
        $id_user = getIdUserFromApiKey($apiKey);
        $role = getRoleFromApiKey($apiKey);

        if($role >= 3){
            $exist = selectDB("SERVICE", "*", "id_service=".$id_service." AND id_user_booking=".$id_user, "bool");
            if(!$exist) {
                exit_with_message("Error, this reservation don't exist for you", 403);
            }
        } elseif ($role <= 2){
            $exist = selectDB("SERVICE", "*", "id_service=".$id_service, "bool");
            if(!$exist) {
                exit_with_message("Error, this reservation don't exist", 500);
            }
        }

        $data = selectDB("LINKSERVICEVEHICLE", "*", "id_service=".$id_service)[0];

        if(deleteDB("LINKSERVICEVEHICLE", "id_service=".$id_service)){
            if(deleteDB("SERVICE", "id_service=".$id_service)){
                exit_with_message("Reservation canceled", 200);
            } else {
                insertDB("LINKSERVICEVEHICLE", ["id_service", "id_vehicule"], [$data["id_service"], $data["id_vehicule"]]);
            }
        }

        exit_with_message("Error when deleting your reservation");

    }

    //------------------------------------------------------------------------------------------

    private function returnDesDataForBookedBookingVehicle($data){

        $vehiculetArray = [];
        for ($i=0; $i < count($data) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($data, $i);
            $service = returnService($data, $i);
            $service->addUser(returnUser($data, $i));
            $vehiculetArray[$i]->addService($service);
        }

        exit_with_content($vehiculetArray);
    }
}