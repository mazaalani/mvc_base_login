
<?php 
  echo '<main>';
  echo "<h1>Blog Spot</h1>";
  if(isset($donnees["messageErreur"]))
    echo "<span>" . $donnees["messageErreur"] . "</span>";
  else
  {
    if (isset($donnee['resultatRecherche']))
      $articles = $donnee['resultatRecherche'];
    else
      $articles = $donnees['articles']; 
      
    while($rangee = mysqli_fetch_assoc($articles))
    { 
        //à chaque tour de boucle, $rangee vaut le nouvel article        
        echo '<article><h2>'. htmlspecialchars($rangee["titre"], ENT_QUOTES). '</h2>' ;
        //affichage lien modification pour l'usager sur les articles dont il est l’auteur
        if(isset($_SESSION["usager"]) && $_SESSION["usager"] == $rangee['idAuteur'])
        {
          echo "<div><a class='link' href='index.php?commande=PageArticle&idArticle=" . $rangee["id"] . "'> &#9851 Modifier cet article</a>";
          echo "<a class='link' href='index.php?commande=SupprimerArticle&idArticle=" . $rangee["id"] . "&idAuteur=" .$rangee['idAuteur']. "'> &#9760; Supprimer cet article</a></div>";
        } 
        echo '<p>'. htmlspecialchars($rangee["texte"], ENT_QUOTES). '</p>';
        echo '<span> Écrit par: '. htmlspecialchars($rangee["nom"], ENT_QUOTES). '</span></article>';
        
    }    
  }  
  echo "</main>";

?>
    

