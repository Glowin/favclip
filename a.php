<?php
/*//If we submitted the form
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
}*/

class  Employee
{
	var $name;
	var $city;
	protected $wage;
	function __get($propName) {
		echo "__get called!<br>";
		$vars = array("name", "city" );
		if (in_array($propName, $vars)) {
			return $this -> $propName;			
		} else {
			return "No such variable!";
		}
	}
}

$employee = new Employee();
$employee ->name = "Mario";

echo $employee -> name."<br />";
echo $employee -> age;

?>