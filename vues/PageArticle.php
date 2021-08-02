<form class='padding' method="POST" action="index.php">
    <fieldset>
        <?php 
            if (isset($donnee['selectionne']))  
            {                
                $article = $donnee['selectionne'];
                while($rangee = mysqli_fetch_assoc($article))
                {
                    //à chaque tour de boucle, $rangee vaut un nouvel article
                    $titreArticle = $rangee['titre'];
                    $texteArticle = $rangee['texte'];
                    $auteur = $rangee['idAuteur'];
                }
                // revalidation que l'utilisateur est bien l'auteur de l'article
                if($auteur !== $_SESSION['usager']) 
                {
                    header('Location: index.php');
                    die();
                }                
                $idModif = $_REQUEST['idArticle'];
                
            }
            
        ?>
            Titre : <input type="text" name='titre' value='<?= htmlspecialchars($titreArticle ?? "", ENT_QUOTES)  ?>' />
            <textarea name="article" rows="10" cols="100" > <?= htmlspecialchars($texteArticle ?? '...' , ENT_QUOTES)  ?> </textarea>
            <input class='btn' type="submit" name="commande" value="Enregistrer"/>            
            <input type="hidden" name="idModification" value="<?= $idModif ?>"/>
            <?php
                if(isset($donnees["messageErreur"]))
                echo "<span>" . $donnees["messageErreur"] . "</span>";
            ?>
           
    </fieldset>
</form>