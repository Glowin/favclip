<?php
//If we submitted the form
if(isset($_POST['submitMe']))
{
     echo("Hello, " . $_POST['name'] . ", we submitted your form!");
}
//If we haven't submitted the form
else
{
?>
    <form action="http://localhost/favclip/a.php" method="POST">
    <input type="text" name="name"><br>
    <input type="submit" value="submit" name="submitMe">
    </form>
<?php
}
?>