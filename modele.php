<?php
    define("SERVER", "localhost");
    define("USERNAME", "root");
    define("PASSWORD", "");
    define("DBNAME", "blog");

    function connectDB()
    {
        //se connecter à la base de données
        $c = mysqli_connect(SERVER, USERNAME, PASSWORD, DBNAME);

        if(!$c)
            trigger_error("Erreur de connexion : " . mysqli_connect_error());
        
        //s'assurer que la connection traite du UTF8
        mysqli_query($c, "SET NAMES 'utf8'");

        return $c;
    }

    $connexion = connectDB();

    function obtenir_articles()
    {
        global $connexion;

        $requete = "SELECT titre ,texte ,nom, id, idAuteur  FROM articles JOIN usagers ON idAuteur = username ORDER BY id DESC";
        //exécuter la requête avec mysqli... 
        $resultats = mysqli_query($connexion, $requete);
        return $resultats;
    }   
    
    function obtenir_article_selectionne($idArticle) 
    {
        global $connexion;

        $requete = "SELECT titre ,texte ,idAuteur , id  FROM articles JOIN usagers ON idAuteur = username WHERE id=$idArticle";
        //exécuter la requête avec mysqli... 
        $resultats = mysqli_query($connexion, $requete);
        return $resultats;
    }    

    function login($username, $password)
    {
        global $connexion;

        if($reqPrep = mysqli_prepare($connexion, "SELECT password FROM usagers WHERE username=?"))
        {
            //lier les paramètres
            mysqli_stmt_bind_param($reqPrep, 's', $username);
            //exécuter la requête
            mysqli_stmt_execute($reqPrep);
            //obtenir le résultat (utilisable par la suite avec mysqli_fetch_array)
            $resultats = mysqli_stmt_get_result($reqPrep);

            if(mysqli_num_rows($resultats) > 0) 
            {
                $rangee = mysqli_fetch_assoc($resultats);
                $motDePasseEncrypte = $rangee["password"];
                if(password_verify($password, $motDePasseEncrypte))
                    return true;
                else    
                    return false;
            }
            else
                return false;
        }        
    }   

    function ajouter_article($auteur, $titre, $article)
    {
        global $connexion;

        if($reqPrep = mysqli_prepare($connexion, "INSERT INTO articles (idAuteur, titre, texte) VALUES (?, ?, ?)"))
        {
            //lier les paramètres
            mysqli_stmt_bind_param($reqPrep, 'sss', $auteur, $titre, $article);
            //exécuter la requête
            mysqli_stmt_execute($reqPrep);
            //est-ce que l'insertion a fonctionné
            if(mysqli_affected_rows($connexion) > 0)
            {                
                return true;
            }
            else
            {
                die("Erreur lors de l'insertion." . mysqli_error($connexion));
            }
        }
    }

    function supprimer_article($idArticle)
    {
        global $connexion;

        $requete = "DELETE FROM articles WHERE id=$idArticle";
        //exécuter la requête avec mysqli... 
        $resultats = mysqli_query($connexion, $requete);
        //verification requete executée ou pas
        if(mysqli_affected_rows($connexion) > 0)
        {
            return true;
        }
        else
        {
            die("Erreur lors de la suppression." . mysqli_error($connexion));
        }
        
    }

    function modifier_article($titre, $article, $id)
    {
        global $connexion;
        
        if($reqPrep = mysqli_prepare($connexion, "UPDATE articles SET titre=?, texte=? WHERE id=?"))
        {
            //lier les paramètres
            mysqli_stmt_bind_param($reqPrep, 'ssi', $titre, $article, $id);
            //exécuter la requête
            mysqli_stmt_execute($reqPrep);
            //est-ce que l'insertion a fonctionné
            if(mysqli_affected_rows($connexion) > 0)
            {
                return true;
            }
            else
            {
                die("Erreur lors de l'insertion." . mysqli_error($connexion));
            }
        }
    }

    function chercher_article($txtRecherch)
    {
        global $connexion;

        $r = "%".$txtRecherch."%";
        
        if($reqPrep = mysqli_prepare($connexion, "SELECT titre ,texte ,nom, id, idAuteur  FROM articles JOIN usagers ON idAuteur = username WHERE texte LIKE ?"))
        {
            mysqli_stmt_bind_param($reqPrep, "s", $r);
            mysqli_stmt_execute($reqPrep);
            $resultat = mysqli_stmt_get_result($reqPrep);
            if (mysqli_num_rows($resultat) > 0) 
                return $resultat;
            else 
                return false;
        }
        else           
            return false;           
    }

?>