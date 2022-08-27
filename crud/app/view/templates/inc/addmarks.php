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
	<form action="/marks/add" method="get">
		<div class="mb-3">
			<label for="group" class="form-label">Группа</label>
			<select class="form-select" name="group" id="group">
				<option selected value="">Выберите группу</option>
				<?php foreach ($data['groups'] as $group): ?>
					<?php if (isset($_GET['group']) && $_GET['group'] == $group['id']): ?>
						<option selected value="<?= intval($group['id']) ?>">
							<?= htmlspecialchars($group['name']) ?>
						</option>
					<?php else: ?>
						<option value="<?= intval($group['id']) ?>">
							<?= htmlspecialchars($group['name']) ?>
						</option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</div>
		<button type="submit" class="btn btn-primary">Выбрать</button>
	</form>

	<?php if (isset($_GET['group']) && $_GET['group'] != ''): ?>
		<?php foreach ($data['groups'] as $group): ?>
			<?php if ($group['id'] == $_GET['group']): ?>
				<form action="/marks/add?group=<?= intval($_GET['group']) ?>" method="post">
					<div class="mb-3">
						<label for="student" class="form-label">Студент</label>
						<select class="form-select" name="student" id="student">
							<option selected value="">Выберите студента</option>
							<?php foreach ($data['students'] as $student): ?>
								<option value="<?= intval($student['std_id']) ?>">
									<?= htmlspecialchars($student['std_name']) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3">
						<label for="subject" class="form-label">Предмет</label>
						<select class="form-select" name="subject" id="subject">
							<option selected value="">Выберите предмет</option>
							<?php foreach ($data['subjects'] as $subject): ?>
								<option value="<?= intval($subject['sub_id']) ?>">
									<?= htmlspecialchars($subject['sub_name']) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3">
						<label for="points" class="form-label">Баллы</label>
						<input type="text" class="form-control" name="points" id="points">
					</div>
					<button type="submit" class="btn btn-primary">Добавить</button>
				</form>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/assets/inc/footer.php"; ?>
