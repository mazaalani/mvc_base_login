<form class='center' method="POST" action="index.php">
        Username : <input class='txt-input' type="text" name="user"/><br>
        Password : <input class='txt-input' type="password" name="pass"/><br>
        <input type="submit" name="login" value="Login"/>
        <input type="hidden" name="commande" value="ValidationLogin"/>
</form>
<?php
    if(isset($messageErreur))
        echo "<span>$messageErreur</span>";
?>