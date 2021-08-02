
<html>
    <head>
        <meta charset='utf-8'>
        <title><?php if(isset($donnees["titre"])) echo $donnees["titre"];?></title>
        <link rel="stylesheet" href="vues/CSS/style.css?v=1.18">
    </head>
    <body>
        <nav>
            <div>
                <div>  
                    <?php
                    if(isset($_REQUEST['commande'])) $hidden = $_REQUEST['commande'] == 'Rechercher' ? "" : "hidden" ;
                    else $hidden = "";
                    
                    ?>  
                    <!-- la classe permet de cacher la barre de recherche quand on est pas a la page default -->           
                    <form class='<?= $hidden ?>' method='GET' action="index.php">
                        <input class='txt-input ' type='text' name='texteRecherche'/>
                        <input class='btn' type="submit" name='commande' value='Rechercher'/>
                    </form> 
                </div>               
                <div>
        <?php
            if(!isset($_SESSION["usager"]))
            {
        ?>
            <a class='link' href="index.php?commande=FormLogin">S'authentifier<a>            
        <?php        
            }
            else
            {
        ?>                     
                    <a class='link' href="index.php?commande=PageArticle">Nouvel Article<a>  
                    <a class='btn' href="index.php?commande=Logout">Logout<a><span> (connecté en tant que: <?= $_SESSION['usager'] ?>)</span> 
                </div>   
        <?php
            }        
        ?>                 
            </div>
        </nav>  
        