<!DOCTYPE html>
<html>
<head>

	<link href="css/bootstrap.css" rel="stylesheet"/>
	<meta charset="utf-8" />
	<title>Activite 2</title>

	<?php
		$host = 'localhost';
		$db   = 'my_activities';
		$user = 'root';
		$pass = 'root';
		$charset = 'utf8';
		$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
		
		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];
		try {
			 $pdo = new PDO($dsn, $user, $pass, $options);
		} catch (PDOException $e) {
			echo $e->getMessage();
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}
	?>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			
				<h1>All Users</h1>
				<form action="all_users.php" method="get" >
					<p>Start with letter</p><input type="text" id="name" name="name" >
					<p>and status is: </p> <select name="status">
												<option value="option1">Waiting for account validation</option>
												<option value="option2">Active account</option>
												<option value="option3">Waiting for account deletion</option>
												
											</select>
				</form>
				<input type="button" value="ok" />
				
				<table class="table table-bordered table-striped">
					<tr>
						<th>Id</th>
						<th>Username</th>
						<th>Email</th>
						<th>Status</th>
					<tr>
					
					<?php
						$status_id = 2;
						$lettreDebut = 'e';
					
						$stmt = $pdo->query('SELECT users.id AS id, username, email, name
											 FROM users
											 JOIN status
											 ON users.status_id = status.id
											 WHERE status_id = '.$status_id.'
											 AND username LIKE \''.$lettreDebut.'%'.'\'');
						while ($row = $stmt->fetch()) {
							echo "<tr>";
								echo "<td>".$row['id']."</td>";
								echo "<td>".$row['username']."</td>";
								echo "<td>".$row['email']."</td>";
								echo "<td>".$row['name']."</td>";
							echo "</tr>";
						}
					?>
				</table>			
			</div>
		</div>
	</div>
</body>
</html>
