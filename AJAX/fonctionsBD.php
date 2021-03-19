<?php

/* Fonction permettant la connexion à la base de données  */
function getConnexion()
{
    static $dbb = null;
    if ($dbb === null) {
        try {
            $dbb = new PDO('mysql:host=hhva.myd.infomaniak.com;dbname=hhva_milazimidrz', 'hhva_milazimidrz', 'X9iwWMeCpF');

        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    return $dbb;
}

/* Fonction permettant de créer un utilisateur  */
function inscription($nom, $prenom, $email, $login, $tel, $adresse, $token)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("INSERT INTO CLIENT (NOM_CLIENT, PRENOM_CLIENT, EMAIL_CLIENT, TELEPHONE_CLIENT, LOG_CLIENT, ADRESSE_CLIENT, isEmailConfirmed, token ) VALUES (:nom, :prenom, :email, :tel, :login, :adresse, 0, :token)");
        $request->bindParam(':nom', $nom, PDO::PARAM_STR);
        $request->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $request->bindParam(':email', $email, PDO::PARAM_STR);
        $request->bindParam(':login', $login, PDO::PARAM_STR);
        $request->bindParam(':tel', $tel, PDO::PARAM_STR);
        $request->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $request->bindParam(':token', $token, PDO::PARAM_STR);
        $request->execute();


    } catch (PDOException $e) {
        throw $e;
    }
}


/* Fonction permettant verifier si le client existe dans la base de données */
function checkEmailExist($email)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT * FROM CLIENT WHERE EMAIL_CLIENT = :email;");
        $request->bindParam(':email', $email, PDO::PARAM_STR);
        $request->execute();
        return $request->fetch();
    } catch (PDOException $e) {
        throw $e;
    }


}

/* Fonction permettant d'inscrire un prestataire dans la base de données */
function inscriptionPrestataire($nom, $prenom, $email, $tel, $login, $token)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("INSERT INTO PRESTATAIRE (NOM_PRESTATAIRE, PRENOM_PRESTATAIRE, EMAIL_PRESTATAIRE, TELEPHONE_PRESTATAIRE, LOG_PRESTATAIRE, ADMIN_PRESTATAIRE, token) VALUES (:nom, :prenom, :email, :tel, :login, NULL, :token)");
        $request->bindParam(':nom', $nom, PDO::PARAM_STR);
        $request->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $request->bindParam(':email', $email, PDO::PARAM_STR);
        $request->bindParam(':login', $login, PDO::PARAM_STR);
        $request->bindParam(':tel', $tel, PDO::PARAM_STR);
        $request->bindParam(':token', $token, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/* Fonction permettant de verifie si le prestataire existe dans la base de données */
function checkEmailExistPrestataire($email)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT * FROM PRESTATAIRE WHERE EMAIL_PRESTATAIRE = :email;");
        $request->bindParam(':email', $email, PDO::PARAM_STR);
        $request->execute();
        return $request->fetch();
    } catch (PDOException $e) {
        throw $e;
    }

}

/**
 * Récupère tout les smartphones avec les informations de la table type_de_smartphone
 */
function getSmartphoneVente($limite)
{
    try {
        $query = "SELECT SMARTPHONE.IDSMARTPHONE, TYPE_DE_SMARTPHONE.IDTYPE_DE_SMARTPHONE, TYPE_DE_SMARTPHONE.MARQUE_TYPE_DE_SMARTPHONE, TYPE_DE_SMARTPHONE.MODEL_TYPE_DE_SMARTPHONE, TYPE_DE_SMARTPHONE.COULEUR_TYPE_DE_SMARTPHONE, TYPE_DE_SMARTPHONE.GB_TYPE_DE_SMARTPHONE, SMARTPHONE.PRIX_VENTE_SMARTPHONE, SMARTPHONE.IMG_SMARTPHONE
    FROM SMARTPHONE
    JOIN TYPE_DE_SMARTPHONE
    ON TYPE_DE_SMARTPHONE.IDTYPE_DE_SMARTPHONE = SMARTPHONE.IDTYPE_DE_SMARTPHONE
    WHERE SMARTPHONE.PRIX_VENTE_SMARTPHONE IS NOT NULL AND SMARTPHONE.IDRDV IS NULL";
        $request = getConnexion()->prepare($query);
        $request->execute();
        $request = $request->fetchAll(PDO::FETCH_ASSOC);

        $result=array();
        if ($limite){
            for ($i = 0; $i <= count($request); $i++) {
                if ($i < 5){
                    $result[] = $request[$i];
                }
            }
        }else{
            for ($i = 0; $i <= count($request); $i++) {
                if ($i >= 5){
                    $result[] = $request[$i];
                }
            }
        }
        return $result;
    } catch (PDOException $e) {
        throw $e;
    }
}



/**
 * Récupère les informations d'un smartphone du catalogue
 */
function getOneSmartphone($idSMART)
{
    try {
        $request = getConnexion()->prepare("SELECT SMARTPHONE.IDSMARTPHONE, TYPE_DE_SMARTPHONE.IDTYPE_DE_SMARTPHONE, TYPE_DE_SMARTPHONE.MARQUE_TYPE_DE_SMARTPHONE, TYPE_DE_SMARTPHONE.MODEL_TYPE_DE_SMARTPHONE, TYPE_DE_SMARTPHONE.COULEUR_TYPE_DE_SMARTPHONE, TYPE_DE_SMARTPHONE.GB_TYPE_DE_SMARTPHONE, SMARTPHONE.PRIX_VENTE_SMARTPHONE, SMARTPHONE.IMG_SMARTPHONE
      FROM SMARTPHONE
      JOIN TYPE_DE_SMARTPHONE
      ON TYPE_DE_SMARTPHONE.IDTYPE_DE_SMARTPHONE = SMARTPHONE.IDTYPE_DE_SMARTPHONE
      WHERE SMARTPHONE.PRIX_VENTE_SMARTPHONE IS NOT NULL AND SMARTPHONE.IDRDV IS NULL AND IDSMARTPHONE = :idSMART");
        $request->bindParam(':idSMART', $idSMART, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}


/* Fonction permettant de reprendre tous les smartphones de la clase TYPE_DE_SMARTPHONE */
function getTypeSmartphone()
{
    try {
        $query = "SELECT * FROM TYPE_DE_SMARTPHONE";
        $request = getConnexion()->prepare($query);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/* Fonction permettant de reprendre tous les smartphones de la clase TYPE_DE_SMARTPHONE de modele "Iphone" sans doublons*/
function getModelIphone()
{
    try {
        $query = "SELECT DISTINCT MODEL_TYPE_DE_SMARTPHONE FROM TYPE_DE_SMARTPHONE WHERE MARQUE_TYPE_DE_SMARTPHONE = 'Iphone'";
        $request = getConnexion()->prepare($query);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/* Fonction permettant de reprendre tous les smartphones de la clase TYPE_DE_SMARTPHONE de modele "Samsung" sans doublons*/
function getModelSamsung()
{
    try {
        $query = "SELECT DISTINCT MODEL_TYPE_DE_SMARTPHONE FROM TYPE_DE_SMARTPHONE WHERE MARQUE_TYPE_DE_SMARTPHONE = 'Samsung'";
        $request = getConnexion()->prepare($query);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/* Fonction permettant de reprendre tous les smartphones de la clase TYPE_DE_SMARTPHONE de modele "Iphone"*/
function getModelIphoneAdmin()
{
    try {
        $query = "SELECT * FROM TYPE_DE_SMARTPHONE WHERE MARQUE_TYPE_DE_SMARTPHONE = 'Iphone'";
        $request = getConnexion()->prepare($query);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/* Fonction permettant de reprendre tous les smartphones de la clase TYPE_DE_SMARTPHONE de modele "Samsung"*/
function getModelSamsungAdmin()
{
    try {
        $query = "SELECT *  FROM TYPE_DE_SMARTPHONE WHERE MARQUE_TYPE_DE_SMARTPHONE = 'Samsung'";
        $request = getConnexion()->prepare($query);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/* Fonction permettant de reprendre toutes les réparations de la classe TYPE_REPARATION*/
function getTypeReparation()
{
    try {
        $query = "SELECT * FROM TYPE_REPARATION";
        $request = getConnexion()->prepare($query);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}


/* Fonction permettant d'ajouter un smartphone pour la vente dans la classe SMARTPHONE*/
function ajouterImageSmartphone($idPrestataire, $idType, $identifiant, $prix, $image)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("INSERT INTO SMARTPHONE(IDPRESTATAIRE, IDTYPE_DE_SMARTPHONE, IDRDV, IDCLIENT, PRIX_ACHAT_SMARTPHONE, IDENTIFIANT_SMARTPHONE, DATE_VENTE_SMARTPHONE, PRIX_VENTE_SMARTPHONE, IMG_SMARTPHONE) VALUES (:idPrestataire, :idType, NULL, NULL, NULL, :identifiant, NULL, :prix, :image)");
        $request->bindParam(':idPrestataire', $idPrestataire, PDO::PARAM_INT);
        $request->bindParam(':idType', $idType, PDO::PARAM_INT);
        $request->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
        $request->bindParam(':prix', $prix, PDO::PARAM_INT);
        $request->bindParam(':image', $image, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/* Fonction permettant d'id du client*/
function trouverID($mail)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT IDCLIENT FROM CLIENT WHERE EMAIL_CLIENT = '$mail'");
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/* Fonction permettant d'ajouter un rendez-vous pour un client pour une reparation dans la table RDV*/
function ajouterDansRdv($idcli, $dateheure)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("INSERT INTO RDV(IDCLIENT, IDPRESTATAIRE, ADRESSE_RDV, DATEHEURE_RDV, TYPE_RDV) VALUES (:idclient,NULL,NULL,:dateHeureRdv,'Réparation')");
        $request->bindParam(':idclient', $idcli, PDO::PARAM_INT);
        $request->bindParam(':dateHeureRdv', $dateheure, PDO::PARAM_STR);
        $request->execute();
        return $connexion->lastInsertId();
    } catch (PDOException $e) {
        throw $e;
    }
}

/* Fonction permettant d'ajouter un client pour une vente dans la table RDV*/
function ajouterRdvVente($idcli, $dateheure)
{
    try {
        $connexion = getConnexion();
        var_dump($idcli);
        var_dump($dateheure);
        $request = $connexion->prepare("INSERT INTO RDV(IDCLIENT, IDPRESTATAIRE, ADRESSE_RDV, DATEHEURE_RDV, TYPE_RDV) VALUES (:idclient,NULL,NULL,:dateHeureRdv,'Vente')");
        $request->bindParam(':idclient', $idcli, PDO::PARAM_INT);
        $request->bindParam(':dateHeureRdv', $dateheure, PDO::PARAM_STR);
        $request->execute();
        return $connexion->lastInsertId();
    } catch (PDOException $e) {
        throw $e;
    }
}

/* Fonction permettant de modifier le prix de vente d'un smartphone*/
function updatePrixVente($idPrix, $idSmart)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("UPDATE SMARTPHONE SET PRIX_VENTE_SMARTPHONE= :idPrix WHERE IDSMARTPHONE = :idSmart");
        $request->bindParam(':idPrix', $idPrix, PDO::PARAM_INT);
        $request->bindParam(':idSmart', $idSmart, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/* Fonction permettant de modifier le rendez-vous d'un client*/
function updateRdvSmartphone($idRdv, $idSmart)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("UPDATE SMARTPHONE SET IDRDV= :idRdv WHERE IDSMARTPHONE = :idSmart");
        $request->bindParam(':idRdv', $idRdv, PDO::PARAM_INT);
        $request->bindParam(':idSmart', $idSmart, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/* Fonction permettant d'ajouter une réparation dans la classe REPARATION*/
function ajouterDansReparation($idrdv, $idsmart, $idtyperepa, $descrep, $daterep)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("INSERT INTO REPARATION(IDRDV, IDSMARTPHONE, IDTYPE_REPARATION, IDPRESTATAIRE, DESCRIPTION_REPARATION, DATE_REPARATION) VALUES (:idrdv, :idsmartphone, :idtypeRepa, NULL, :descRepa, :dateRepa)");
        $request->bindParam(':idrdv', $idrdv, PDO::PARAM_INT);
        $request->bindParam(':idsmartphone', $idsmart, PDO::PARAM_INT);
        $request->bindParam(':idtypeRepa', $idtyperepa, PDO::PARAM_INT);
        $request->bindParam(':descRepa', $descrep, PDO::PARAM_STR);
        $request->bindParam(':dateRepa', $daterep, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

//AVEC HTMLENTITIES
function ajouterDansReparationHTMLENTITIES($idrdv, $idsmart, $idtyperepa, $descrep, $daterep)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("INSERT INTO REPARATION(IDRDV, IDSMARTPHONE, IDTYPE_REPARATION, IDPRESTATAIRE, DESCRIPTION_REPARATION, DATE_REPARATION) VALUES (:idrdv, :idsmartphone, :idtypeRepa, NULL, :descRepa, :dateRepa)");
        $request->bindParam(':idrdv', $idrdv, PDO::PARAM_INT);
        $request->bindParam(':idsmartphone', $idsmart, PDO::PARAM_INT);
        $request->bindParam(':idtypeRepa', $idtyperepa, PDO::PARAM_INT);
        $request->bindParam(htmlentities(':descRepa'), htmlentities($descrep), PDO::PARAM_STR);
        $request->bindParam(':dateRepa', $daterep, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

// AVEC HTMLSPECIALCHARS
function ajouterDansReparationHTMLSPECIALCHARS($idrdv, $idsmart, $idtyperepa, $descrep, $daterep)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("INSERT INTO REPARATION(IDRDV, IDSMARTPHONE, IDTYPE_REPARATION, IDPRESTATAIRE, DESCRIPTION_REPARATION, DATE_REPARATION) VALUES (:idrdv, :idsmartphone, :idtypeRepa, NULL, :descRepa, :dateRepa)");
        $request->bindParam(':idrdv', $idrdv, PDO::PARAM_INT);
        $request->bindParam(':idsmartphone', $idsmart, PDO::PARAM_INT);
        $request->bindParam(':idtypeRepa', $idtyperepa, PDO::PARAM_INT);
        $request->bindParam(htmlspecialchars(':descRepa'), htmlspecialchars($descrep), PDO::PARAM_STR);
        $request->bindParam(':dateRepa', $daterep, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

// AVEC STRIP_TAGS
function ajouterDansReparationSTRIP_TAGS($idrdv, $idsmart, $idtyperepa, $descrep, $daterep)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("INSERT INTO REPARATION(IDRDV, IDSMARTPHONE, IDTYPE_REPARATION, IDPRESTATAIRE, DESCRIPTION_REPARATION, DATE_REPARATION) VALUES (:idrdv, :idsmartphone, :idtypeRepa, NULL, :descRepa, :dateRepa)");
        $request->bindParam(':idrdv', $idrdv, PDO::PARAM_INT);
        $request->bindParam(':idsmartphone', $idsmart, PDO::PARAM_INT);
        $request->bindParam(':idtypeRepa', $idtyperepa, PDO::PARAM_INT);
        $request->bindParam(strip_tags(':descRepa'), strip_tags($descrep), PDO::PARAM_STR);
        $request->bindParam(':dateRepa', $daterep, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

function ajouterDansSmartphone($idtypeSmart, $idrdv, $idclient)
{ /*Fonction permettant d'ajouter un smartphone dans la table smartphone*/
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("INSERT INTO SMARTPHONE(IDPRESTATAIRE, IDTYPE_DE_SMARTPHONE, IDRDV, IDCLIENT, PRIX_ACHAT_SMARTPHONE, IDENTIFIANT_SMARTPHONE, DATE_VENTE_SMARTPHONE, PRIX_VENTE_SMARTPHONE, IMG_SMARTPHONE) VALUES (NULL, :idType, :idRdv, :idClient, NULL, NULL, NULL, NULL, NULL)");
        $request->bindParam(':idType', $idtypeSmart, PDO::PARAM_INT);
        $request->bindParam(':idRdv', $idrdv, PDO::PARAM_INT);
        $request->bindParam(':idClient', $idclient, PDO::PARAM_INT);
        $request->execute();
        return $connexion->lastInsertId();
    } catch (PDOException $e) {
        throw $e;
    }
}


/* PARTIE SECURITE - XSS */
function xssDonneeConnexionAdmin($email, $password)
{
    try {
        $bdd = getConnexion();
        $request = $bdd->prepare("INSERT INTO LOGIN_XSS(EMAIL_XSS, MDP_XSS)VALUES(:email, :password)");
        $request->bindParam(':email', $email, PDO::PARAM_STR);
        $request->bindParam(':password', $password, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}


?>
