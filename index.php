<!DOCTYPE html>
<html>
<head>
	<title>Yamaya Info Dep. Staffs</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script type="text/javascript" src="./js/jquery.js"></script>
	<script type="text/javascript" src="./js/bootstrap.js"></script>
</head>
<body>
	<?php
		$host = "localhost";
		$dbname = "test";
		$user = "root";
		$password = "root";
		try {
			$pdo = new PDO(
				"mysql:host=$host;dbname=test;charset=utf8",
				$user, $password, 
				array(PDO::ATTR_EMULATE_PREPARES => false)
			);
		} catch (PDOException $e) {
			exit('データベース接続失敗。'.$e->POSTMessage());
		}
	?>

	<br/><h1 class="text-center">やまや本社情報システム部の皆さま</h1><br/>

	<div class="container">
		<?php 
			$alert = array();
			if(isset($_POST['action'])) {
				switch ($_POST['action']) {
					case 'insert':
						if (empty($_POST['name'])) {
							$alert['insert'] = "お名前を入力してください。";
							break;
						}

						$sql = "INSERT INTO staffs(name, seat, hobby) VALUES (:name, :seat, :hobby)";
						$stmt = $pdo -> prepare($sql);
						$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
						$stmt->bindValue(':seat', $_POST['seat'], PDO::PARAM_INT);
						$stmt->bindParam(':hobby', $_POST['hobby'], PDO::PARAM_STR);
						$stmt->execute();
						break;
					
					case 'delete':
						$sql = 'DELETE FROM staffs where id = :id';
						$stmt = $pdo -> prepare($sql);
						$stmt -> bindParam(':id', $_POST['id'], PDO::PARAM_INT);
						$stmt -> execute();
						break;

					default:
						break;
				}
			}
	?>

	<!-- insertのエラー処理 -->
		<?php if (isset($alert['insert'])):	?>
			<div class="alert alert-danger alert-dismissible" 
			role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="閉じる">
				<span aria-hidden="true">×</span></button>
				<strong><?php echo $alert['insert']; ?></strong>
			</div>
		<?php endif ?>

		<div class="container col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">ご入力フォーム</h3>
				</div>

				<div class="panel-body">
					<form action="index.php" method="post">
						<input type="hidden" name="action" value="insert">
						
						<div class="form-group">
							<p>お名前</p>
							<input type="text" name="name" maxlength="30">
						</div>
						
						<div class="form-group">
							<p>御席の場所</p>
							<input type="number" name="seat" min="0">
						</div>
						
						<div class="form-group">
							<p>ご趣味</p>
							<input type="textarea" name="hobby">
						</div>
						
						<button type="submit" class="btn btn-default">
							追加
						</button>
					</form>
				</div>
			</div>
		</div>

		<div class="container col-md-9">
			<table class="table table-striped table-responsive">
				<?php $stmt = $pdo->query("SELECT * FROM staffs"); ?>
				<tr>
					<th>お名前</th>
					<th>御席の場所</th>
					<th>ご趣味</th>
					<th>削除ボタン</th>
				</tr>

				<?php while($row = $stmt -> fetch(PDO::FETCH_ASSOC)): ?>
					<tr>
						<td><?php echo $row['name']; ?></td>
						<td><?php echo $row['seat']; ?></td>
						<td><?php echo $row['hobby']; ?></td>	
						
						<td>
							<form action="index.php" method="post">
								<input type="hidden" name="action" 
								value="delete">
								<input type="hidden" name="id" 
								value=<?php echo $row['id']; ?>>
								<button type="submit" 
									class="btn btn-default">
									削除
								</button>
							</form>
						</td>
					</tr>
				<?php endwhile ?>
			</table>
		</div>
	</div>
</body>
</html>