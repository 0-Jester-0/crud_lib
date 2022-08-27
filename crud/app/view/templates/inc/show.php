<?php /**
 * @var $data array
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/assets/inc/header.php"; ?>

<div class="container">
	<div class="container">
		<?php if (isset($_SESSION['error'])): ?>
			<h6 class="error"><?= $_SESSION['error'] ?></h6>
			<?php unset($_SESSION['error']) ?>
		<?php endif; ?>
	</div>

	<table class="table table-dark table-striped table-bordered">
		<thead>
		<tr>
			<?php foreach ($data['namesColumns'] as $column): ?>
				<th><?= $column ?></th>
			<?php endforeach; ?>
			<th>Обновление/Удаление</th>
		</tr>
		<?php $keysColumns = array_keys($data['namesColumns']) ?>
		</thead>
		<?php foreach ($data['result'] as $item): ?>
			<tbody>
			<tr>
				<?php foreach ($keysColumns as $column): ?>
					<?php if (array_key_exists($column, $item)): ?>
						<?php if (isset($data[$column . 's'])): ?>
							<?php foreach ($data[$column . 's'] as $subItem): ?>
								<?php if ($item[$column] == $subItem['id']): ?>
									<td><?= htmlspecialchars($subItem['name']) . "(" . intval($subItem['id']) . ")" ?></td>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<td><?= htmlspecialchars($item[$column]) ?></td>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
				<td>
					<div class="container">
						<?php if (lcfirst($data['page']) == "marks"): ?>
							<?php foreach ($data['students'] as $student): ?>
								<?php if ($item['student'] == $student['id']): ?>
									<a class="btn btn-success"
									   href="/<?= lcfirst($data['page']) ?>/edit/<?= intval($item['id']) ?>?group=<?=
									   intval($student['group']) ?>">
										Изменить
									</a>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<a class="btn btn-success"
							   href="/<?= lcfirst($data['page']) ?>/edit/<?= intval($item['id']) ?>">
								Изменить
							</a>
						<?php endif; ?>
						<a class="btn btn-danger delete"
						   href="/<?= lcfirst($data['page']) ?>/remove/<?= intval($item['id']) ?>">
							Удалить
						</a>
					</div>
				</td>
			</tr>
			</tbody>
		<?php endforeach; ?>
	</table>

	<div class="container">
		<a class="btn bg-primary" href="/<?= lcfirst($data['page']) ?>/add">
			Добавить запись
		</a>
	</div>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/assets/inc/footer.php" ?>
