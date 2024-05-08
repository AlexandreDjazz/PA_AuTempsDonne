class UserAdmin extends Admin{



    async updateUser(email, prenom, telephone, nom){

        const data = {
            "email" : this.email,
            "prenom" : this.prenom,
            "telephone" : this.telephone,
            "nom" : this.nom
        }

        const response = await this.fetchSync(this.adresse+"/user", this.optionPut(data))
        if(!this.compareAnswer(response, "Impossible de mettre à jour l'utilisateur")){
            popup(dico[this.lang]["update"] + dico[this.lang]["failed"])
            return false
        }
        popup(dico[this.lang]["update"] + dico[this.lang]["success"])
        return response

    }

    /**
     * Retrieve all the users
     * @returns {Promise<boolean>}
     */
    async getAllUser(){
        let response = await this.fetchSync(this.adresse+'/user/all', this.optionGet())
        if(!this.compareAnswer(response, "Impossible de récupérer les utilisateurs")){
            return false
        }
        return response
    }

    /**
     * Only retrieve the waiting for validation users
     * @param id
     * @returns {Promise<void>}
     */
    async getWaitingUser(){
        let response = await this.fetchSync(this.adresse+'/user/validate', this.optionGet())
        if(!this.compareAnswer(response, "Impossible de récupérer les utilisateurs en attente")){
            return false
        }
        return response
    }

    /**
     * Unreference the user using the apikey
     */
    async deleteUser(id = null){
        if(this.apikey === null){
            alert("Apikey null")
            return
        }

        let complementPath = ""
        if(id != null){
            complementPath = `/${id}`
        }

        let response = await this.fetchSync(this.adresse+'/user'+complementPath, this.optionDelete())
        console.log(response)
        if(!this.compareAnswer(response, "Impossible de supprimer l'utilisateur")){
            return false
        }
        popup("Votre compte à bien été désactivé")
        return response

    }
}