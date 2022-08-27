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
	<form action="/rating" method="get">
		<div class="mb-3">
			<label for="group">Группа</label>
			<select class="form-select" name="group">
				<option selected value="">Выберите группу</option>
				<?php foreach ($data['groups'] as $group): ?>
					<option value="<?= intval($group['id']) ?>">
						<?= htmlspecialchars($group['name']) ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="mb-3">
			<button type="submit" class="btn btn-dark">
				Построить рейтинг
			</button>
		</div>
	</form>

	<?php if (isset($_GET['group'])): ?>
		<?php foreach ($data['groups'] as $group): ?>
			<?php if ($group['id'] == $_GET['group']): ?>
				<h5>Рейтинг группы <?= htmlspecialchars($group['name']) ?></h5>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if (isset($data['result']) && $data['result'] != null): ?>
		<table class="table table-dark table-striped table-bordered">
			<thead>
			<tr>
				<th>Студент</th>
				<?php foreach ($data['subjects'] as $subject): ?>
					<th><?= htmlspecialchars($subject) ?></th>
				<?php endforeach; ?>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($data['students'] as $stud_id => $student): ?>
				<tr>
					<td>
						<?= htmlspecialchars($student) ?>
					</td>
					<?php foreach ($data['points'] as $point_id => $points):
						if ($stud_id == $point_id): ?>
							<?php foreach ($points as $point): ?>
								<td>
									<?= intval($point) ?>
								</td>
							<?php endforeach; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/assets/inc/footer.php" ?>
