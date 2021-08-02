<?php

    // index.php est le CONTRÔLEUR de notre application de type MVC (modulaire).
    // Le SQL va dans le modèle et strictement dans le modèle. 
    // Le HTML va dans les vues et strictement dans les vues.

    //démarrer la session
    session_start();
    //réception du paaramètre commande, qui peut arriver en GET ou en POST 
    //(et donc nous utiliserons $_REQUEST)
    if(isset($_REQUEST["commande"]))
    {
        $commande = $_REQUEST["commande"];
    }
    else
    {
        //assigner une commande par défaut -- typiquement la commande qui mène à votre page d'accueil
        $commande = "Accueil";
    }

    //inclure le modele
    require_once("modele.php");

    //structure décisionnelle du contrôleur
    switch($commande)
    {
        case "Accueil":
            //page d'accueil            
            $donnees['articles'] = obtenir_articles();
            $donnees['titre'] = "Accueil BLOG ";
            require_once("vues/Entete.php");
            require_once("vues/Accueil.php");
            require_once("vues/PiedDePage.php");
            break;
        case "FormLogin":
            // formulaire login
            $donnees['titre'] = "Login usager BLOG ";
            require_once("vues/Entete.php");
            require_once("vues/FormulaireLogin.php");
            require_once("vues/PiedDePage.php");
            break;
        case "ValidationLogin":
          if(isset($_REQUEST["user"], $_REQUEST["pass"]))
            {                
                $test = login(trim($_REQUEST["user"]), trim($_REQUEST["pass"]));
                

                if($test)
                {
                    //combinaison valide
                    $_SESSION["usager"] = trim($_REQUEST["user"]);
                    header("Location: index.php");
                }
                else
                {
                    $messageErreur = "Mauvaise combinaison username / password.";
                    require_once("vues/Entete.php");
                    require_once("vues/FormulaireLogin.php");
                    require_once("vues/PiedDePage.php");
                }
            }
            break;    
        case "Logout":
            // Initialisation de la session.
            // Détruit toutes les variables de session
            $_SESSION = array();

            // Si vous voulez détruire complètement la session, effacez également
            // le cookie de session.
            // Note : cela détruira la session et pas seulement les données de session !
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // Finalement, on détruit la session.
            session_destroy();

            //redirection vers la page d'accueil
            header("Location: index.php");
            break;  
        case "PageArticle":    
            $donnees['titre'] = "Article BLOG ";
            //verifier si usager connecté et article selectionné
            if (isset($_SESSION['usager']))  
            {  
                if(isset($_REQUEST['idArticle']))
                {
                    $donnee['selectionne'] = obtenir_article_selectionne($_REQUEST['idArticle']);                    
                    require_once("vues/Entete.php");
                    require_once("vues/PageArticle.php");
                    require_once("vues/PiedDePage.php");
                }  
                else
                {
                    require_once("vues/Entete.php");
                    require_once("vues/PageArticle.php");
                    require_once("vues/PiedDePage.php");
                }                
            }  
            else
            {
                header("Location: index.php");
            }                      
            break;
        case "SupprimerArticle":  
            //revalider que c'est bien l'auteur qui est connecté
            if($_SESSION['usager'] == $_REQUEST['idAuteur'])
            {
                $test = supprimer_article($_REQUEST['idArticle']);
                if($test)
                {
                    header("Location: index.php");
                }
            }
            else
            {
                $donnees["messageErreur"] = "Vous ne pouvez pas supprimer un article dont vous n'étes pas l'auteur!";
                require_once("vues/Entete.php");
                require_once("vues/Accueil.php");
                require_once("vues/PiedDePage.php");
            }
            
            break;
        case "Enregistrer":  
            //revalider que c'est bien l'usager connecté qui est la
            if(isset($_SESSION['usager']))
            {   
                if(isset($_REQUEST['idModification']) && is_numeric($_REQUEST['idModification']) ) //si c'est une modification
                {
                    $t= trim($_REQUEST["titre"]);
                    $a = trim($_REQUEST["article"]);
                    if($t != "" && $a != "")
                        {
                            //procéder à l'insertion                            
                            $test = modifier_article($t, $a,$_REQUEST['idModification']);
                            
                            if($test)
                                header("Location: index.php"); 
                        }    
                    else
                        {
                            
                            $donnees["messageErreur"] = "Il faut entrer des valeurs dans tous les champs.";
                            require_once("vues/Entete.php");
                            require_once("vues/PageArticle.php");
                            require_once("vues/PiedDePage.php");
                            
                        }      
                }
                else 
                {
                    $t = trim($_REQUEST["titre"]);
                    $a = trim($_REQUEST["article"]);
                    if($t != "" && $a != "")
                        {
                            //procéder à l'insertion
                            $test = ajouter_article($_SESSION['usager'], $t, $a);
                            if($test)
                                header("Location: index.php"); 
                        }    
                    else
                        {
                            $donnees["messageErreur"] = "Il faut entrer des valeurs dans tous les champs.";
                            require_once("vues/Entete.php");
                            require_once("vues/PageArticle.php");
                            require_once("vues/PiedDePage.php");
                        }                
                }
            }          
            break;
        case "Rechercher":  
            if(isset($_REQUEST['texteRecherche']))
            {
                $txtRecherch = trim($_REQUEST['texteRecherche']);
                $donnee['resultatRecherche'] = chercher_article($txtRecherch);  
                if (!$donnee['resultatRecherche']) $donnees["messageErreur"] = 'Aucun résultat trouvé.';
                require_once("vues/Entete.php");
                require_once("vues/Accueil.php");
                require_once("vues/PiedDePage.php");              
            }            
            break;
        default:
            //action non traitée, commande invalide -- redirection
            header("Location: index.php");
    }
?>