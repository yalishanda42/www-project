<!DOCTYPE html>

<html>
	<?php if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] > 0) : ?>
		<div class="navbar">
			<img class="position-left" src="images/logo.png">
			<a class="position-left" href="index.php">Home</a>
			<a class="position-left" href="import.php">Import</a>
			<a class="position-left" href="export.php">Export</a>
			<a class="position-left" href="browse.php">My tests</a>
			<a class="position-right" href="exit.php">Log Out</a>
		</div>
	<?php else : ?>
		<div class="navbar">
			<img class="position-left" src="images/logo.png">
			<a class="positio-left" href="index.php">Home</a>
			<a class="position-right" href="signup.php">Sign Up</a>
			<a class="position-right" href="login.php">Log In</a>
		</div>
	<?php endif;?>
</html>