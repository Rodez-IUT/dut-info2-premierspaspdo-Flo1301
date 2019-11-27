<?php
$host = 'localhost';
$db   = 'my-activities';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
     throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Si demande de suppression
if (isset($_GET["action"]) AND $_GET["action"] == "askDeletion"
    AND isset($_GET["status_id"]) AND isset($_GET["user_id"])) {
	
	// Commencer la transaction
	$pdo->beginTransaction();
	
	// Enregistrer l'action dans les logs
	$insert = $pdo->prepare("INSERT INTO action_log (action_date, action_name, user_id) VALUES (?, ?, ?)");
	$insert->execute([date("Y-m-d H:i:s"), $_GET["action"], $_GET["user_id"]]);
	
	// Enoncé erronné
	$probleme = $pdo->query("SELECT uneValeur FROM uneTable");
	
	// Update le statut de l'user
	$update = $pdo->prepare("UPDATE users SET status_id = ? WHERE id = ?");
	$update->execute([$_GET["status_id"], $_GET["user_id"]]);
	
	// Commit la transaction
	$pdo->commit();
}

if (isset($_GET["submit"]) AND isset($_GET["statut"]) AND isset($_GET["lettre"])) {
	$status_id = $_GET["statut"];
	$userLike = $_GET["lettre"] ."%";

	$get = $pdo->prepare("	SELECT u.id, u.username, u.email, s.name status_intitul 
							FROM users u 
							INNER JOIN status s ON s.id = u.status_id 
							WHERE u.status_id = :status_id AND u.username LIKE :userLike
							ORDER BY u.username ASC");

	$get->execute(["status_id" => $status_id, "userLike" => $userLike]);
}

?>
<html>
<head>
	<meta charset="utf-8">
	<link rel="steelsheet" href="css/bootstrap.css" />
</head>
<body>
	<h1>All users</h1>
	<div>
		<form action="" method="GET">
			Nom commençant par la lettre: <input type="text" name="lettre">
			<br>Avec statut:
			<select name="statut">
				<option value="1">Waiting for account validation</option>
				<option value="2">Active account</option>
				<option value="3">Waiting for account deletion</option>
			</select>
			<br><br><input type="submit" name="submit" value="Valider">
		</form>
	</div>
	<table>
		<tr>
			<th>Id</th>
			<th>Username</th>
			<th>Email</th>
			<th>Status</th>
		</tr>
		<?php
		if (isset($get)) {
			while ($fetch = $get->fetch()) {
				?>
				<tr>
					<td><?= $fetch["id"] ?></td>
					<td><?= $fetch["username"] ?></td>
					<td><?= $fetch["email"] ?></td>
					<td><?= $fetch["status_intitul"] ?></td>
					<?php
					
					if ($fetch["status_intitul"] != "Waiting for account deletion") {
						?>
						<td><a href="./all_users.php?status_id=3&user_id=<?= $fetch["id"] ?>&action=askDeletion">Demander la suppression</a></td>
						<?php
					}
					
					?>
				</tr>
				<?php
			}
		}
		?>
	</table>
</body>
</html>