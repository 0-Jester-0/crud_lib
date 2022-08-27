<?php /**
 * @var $data array
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/assets/inc/header.php"; ?>

<div class="container">
	<?php if (isset($data['errors'])): ?>
		<?php foreach ($data['errors'] as $error): ?>
			<h6 class="error"><?= $error ?></h6>
		<?php endforeach; ?>
	<?php endif; ?>
</div>

<div class="container">
	<form action="/<?= lcfirst($data['page']) ?>/add" method="post">
		<?php foreach ($data['inputFields'] as $inputField): ?>
			<div class="mb-3">
				<?php if ($inputField['type'] == "selector"): ?>
					<label for="<?= $inputField['key'] ?>"
						   class="form-label"><?= $inputField['name'] ?></label>
					<select class="form-select" name="<?= $inputField['key'] ?>"
							id="<?= $inputField['key'] ?>">
						<option selected value="">Выберите</option>
						<?php foreach ($data[$inputField['key'] . 's'] as $item): ?>
							<option value="<?= intval($item['id']) ?>">
								<?= htmlspecialchars($item['name']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				<?php elseif ($inputField['type'] == "text"): ?>
					<label for="<?= $inputField['key'] ?>"
						   class="form-label"><?= $inputField['name'] ?></label>
					<input type="text" class="form-control" name="<?= $inputField['key'] ?>"
						   id="<?= $inputField['key'] ?>">
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
		<button type="submit" class="btn btn-primary">Добавить</button>
	</form>
</div>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/assets/inc/footer.php"; ?>
