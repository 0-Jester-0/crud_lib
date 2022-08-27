<?php /**
 * @var $data array
 */
if (!isset($_SESSION))
{
	session_start();
} ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php if (isset($data['pageTitle'])): ?>
		<title><?= htmlspecialchars($data['pageTitle']) ?></title>
	<?php else: ?>
		<title>NoNamePage</title>
	<?php endif; ?>
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
		  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="/assets/mystyle.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
			crossorigin="anonymous"></script>
	<script src="/assets/jquery-3.6.0.min.js"></script>
	<script src="/assets/js/confirmdelete.js"></script>
</head>
<body>
<div class="container-fluid">
	<nav class="navbar navbar-expand-lg navbar-light">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">
				<img src="/assets/img/book-half.svg" alt="" width="30" height="30">
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
					data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
					aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="/rating">
							Рейтинг студентов
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="/groups">Группы</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="/students">Студенты</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="/subjects">Предметы</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="/marks">Оценки</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="/subjectingroup"> Предметы->Группы </a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</div>
