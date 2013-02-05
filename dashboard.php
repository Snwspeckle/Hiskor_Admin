<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
require "/classes/Database.php";

$dbinfo = array(
    "host" => "127.0.0.1",
    "user" => "root",
    "pass" => "",
    "name" => "hiskor"
);

// Creates the PDO Object for queries
$db = new Database ( $dbinfo );
$db->jsonError = true;

session_start();

if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn']!=1)
{
	header("Location: login.php");
	exit();
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="Paweł 'kilab' Balicki - kilab.pl" />
<title>Hiskor Admin Dashboard</title>
<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/navi.css" media="screen" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(function(){
	$(".box .h_title").not(this).next("ul").hide("normal");
	$(".box .h_title").not(this).next("#home").show("normal");
	$(".box").children(".h_title").click( function() { $(this).next("ul").slideToggle(); });
});
</script>
</head>
<body>
<div class="wrap">
	<div id="header">
		<div id="top">
			<div class="left">
				<p>Welcome, <strong><?php echo $_SESSION['username']; ?></strong> [ <a href="logout.php">logout</a> ]</p>
			</div>
			<div class="right">
				<div class="align-right">
					<p>Last login: <strong><?php echo date('jS F Y h:i:s A', $_SESSION['recentLogin']); ?></strong></p>
				</div>
			</div>
		</div>
		<div id="nav">
			<ul>
				<li class="upp"><a href="#">Main control</a>
					<ul>
						<li>&#8250; <a href="">Visit site</a></li>
						<li>&#8250; <a href="">Reports</a></li>
						<li>&#8250; <a href="">Add new page</a></li>
						<li>&#8250; <a href="">Site config</a></li>
					</ul>
				</li>
				<li class="upp"><a href="#">Manage content</a>
					<ul>
						<li>&#8250; <a href="">Show all pages</a></li>
						<li>&#8250; <a href="">Add new page</a></li>
						<li>&#8250; <a href="">Add new gallery</a></li>
						<li>&#8250; <a href="">Categories</a></li>
					</ul>
				</li>
				<li class="upp"><a href="#">Users</a>
					<ul>
						<li>&#8250; <a href="">Show all uses</a></li>
						<li>&#8250; <a href="">Add new user</a></li>
						<li>&#8250; <a href="">Lock users</a></li>
					</ul>
				</li>
				<li class="upp"><a href="#">Settings</a>
					<ul>
						<li>&#8250; <a href="">Site configuration</a></li>
						<li>&#8250; <a href="">Contact Form</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	
	<div id="content">
		<div id="sidebar">
			<div class="box">
				<div class="h_title">&#8250; Management</div>
				<ul id="home">
					<li class="b1"><a class="icon users" href="dashboard.php">Manage Users</a></li>
					<li class="b2"><a class="icon add_user" href="">Add new User</a></li>
				</ul>
			</div>
			
			<div class="box">
				<div class="h_title">&#8250; Tickets</div>
				<ul id="home">
					<li class="b1"><a class="icon page" href="">Manage Tickets</a></li>
					<li class="b2"><a class="icon add_page" href="">Create new Ticket</a></li>
				</ul>
			</div>
			<div class="box">
				<div class="h_title">&#8250; Statistics</div>
				<ul id="home">
					<li class="b1"><a class="icon report" href="user-stats.php">User Stats</a></li>
					<li class="b1"><a class="icon report" href="">Score Stats</a></li>
					<li class="b2"><a class="icon report" href="">Ticket Stats</a></li>
				</ul>
			</div>
			<div class="box">
				<div class="h_title">&#8250; Settings</div>
				<ul id="home">
					<li class="b1"><a class="icon config" href="">Application configuration</a></li>
				</ul>
			</div>
		</div>
		<div id="main">
			<div class="clear"></div>
			<div class="full_w">
				<div align="center" class="h_title">Manage Users</div>
				<table>
					<thead>
						<tr>
							<th scope="col">ID</th>
							<th scope="col">Username</th>
							<th scope="col">Date Created</th>
							<th scope="col">Recent Login</th>
							<th scope="col">Email</th>
							<th scope="col" style="width: 65px;">Modify</th>
						</tr>
					</thead>
						
					<tbody>
						<?php
						// Creates the User table dynamically from the database
						echo "<tr>";

							$db->query("SELECT * FROM `users`")->execute();

							if ($db->getTotalRows()) {

							    while ($result = $db->fetch()) {
								echo "<td class=\"align-center\">{$result['id']}</td>";
								echo "<td>{$result['username']}</td>";
								echo "<td>".date('jS F Y h:i:s A', $result['createdOn'])."</td>";
								echo "<td>".date('jS F Y h:i:s A', $result['recentLogin'])."</td>";
								echo "<td>{$result['email']}</td>";
								echo "<td>";
									echo "<a href=\"#\" class=\"table-icon edit\" title=\"Edit\"></a>";
									echo "<a href=\"#\" class=\"table-icon archive\" title=\"Archive\"></a>";
									echo "<a href=\"#\" class=\"table-icon delete\" title=\"Delete\"></a>";
								echo "</td>";
								}
							}
						echo "</tr>";
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<div id="footer">
		<div class="left">
			<p>Design: <a href="http://kilab.pl">Paweł Balicki</a> | Admin Panel: <a href="">YourSite.com</a></p>
		</div>
		<div class="right">
			<p><a href="">Example link 1</a> | <a href="">Example link 2</a></p>
		</div>
	</div>
</div>

</body>
</html>
