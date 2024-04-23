<?php
include_once './Service/userService.php';
include_once './Models/userModel.php';
include_once './exceptions.php';



function userController($uri, $apiKey) {
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

        	//$userService = new UserService($uri);
            $role = getRoleFromApiKey($apiKey);

            // If a number don't exist after the /user and it's not an admin/modo
            if(!$uri[3]){
                $userService = new UserService();
                exit_with_content($userService->getUserByApikey($apiKey));
            }

            // If a number exist after the /user
            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                $userService = new UserService();
                exit_with_content($userService->getUserById($uri[3]));
            }

            // Take all the users if it's an admin/modo
            elseif($uri[3] == "all" && $role < 3){
                $userService = new UserService();
                exit_with_content($userService->getAllUsers());
            }

            elseif($uri[3] == "validate" && $role < 3){
                $userService = new UserService();
                exit_with_content($userService->getAllWaitingUsers());
            }

            else{
                exit_with_message("You need to be admin to see all the users", 403);
            }
            
            break;


        

        // Create the user
        case 'POST':
         	$userService = new UserService($uri);

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if ( !isset($json['nom']) || !isset($json['prenom']) || !isset($json['email']) || !isset($json['mdp']) || !isset($json['role']))
            {
                exit_with_message("Plz give the name, the lastname the password, the email and the role of the futur user. You can add extra(s) = [telephone]", 403);
            }

            if(isset($json["role"]) && filter_var($json["role"], FILTER_VALIDATE_INT) == false){
                exit_with_message("The role need to be an integer between 1 and 3", 403);
            }

            if($json['role'] < 3){
                exit_with_message("You can't register you as an Admin or modo...", 403); 
            }

            $pattern = '/^\+?[0-9]{5,15}$/';
            if (isset($json['telephone']) && !preg_match($pattern, $json['telephone'])){
                exit_with_message("Le numéro de téléphone $phoneNumber n'est pas valide.", 403);
            }



            $user = new UserModel(1, $json['nom'], $json['prenom'], null, $json['email'], isset($json['telephone']) ? $json['telephone'] : "no_phone", $json['role'], null, 3, 1);

 
            exit_with_content($userService->createUser($user, $json["mdp"]));

            break;

        
        // update the user
        case 'PUT':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

        	$userService = new UserService();

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);
            if (!isset($json["nom"]) || !isset($json["prenom"]) || !isset($json["telephone"]) || !isset($json["email"]) ){
                exit_with_message("Plz give the firstname, lastname, the phone and the email");
            }
            // Valider les données reçues ici
            exit_with_content($userService->updateUser($apiKey, $json["nom"], $json["prenom"], $json["telephone"], $json["email"]));
            break;

        case 'DELETE':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            // Gestion des requêtes DELETE pour supprimer un utilisateur
            $userService = new UserService();
            $role = getRoleFromApiKey($apiKey);

            // If admin and no id specified
            if (!isset($uri[3]) && $role == 1) {
                exit_with_message("No user specified or, you can't unreferenced you as an admin", 403);
            }

            $userId = getIdUserFromApiKey($apiKey);
            
            //If normal user and id specified
            if($uri[3] != $userId && $role != 1){
                exit_with_message("You can't delete a user wich is not you :/", 403);
            }

            if(isset($uri[3])){
                $userService->deleteUserById($uri[3], $apiKey);
            }
            else{
                $userService->deleteUserByApikey($apiKey);
            }

            break;

        default:
            // Gestion des requêtes OPTIONS pour le CORS
            exit_with_message("Not Found", 404); 
            exit();
    }
}

?>