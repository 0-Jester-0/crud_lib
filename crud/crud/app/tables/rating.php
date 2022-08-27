<?php

namespace App\Tables;

use Core\DB\DB;
use Core\View;

class Rating
{
	protected array $fieldsToCheck = ['group'];
	protected string $path = "rating";

	public array $array = [];
	public array $errors = [];

	protected object $view;

	public function __construct()
	{
		$this->view = new View();
	}

	/**
	 * @return void
	 * @throws \Exception
	 */
	public function showTable()
	{
		$this->array ['namePage'] = ucfirst($this->path);
		$this->array ['pageTitle'] = ucfirst($this->path);
		$this->array ['groups'] = Groups::getAll();
		if ("GET" == $_SERVER["REQUEST_METHOD"])
		{
			if (isset($_GET['group']))
			{
				$groupId = $_GET['group'];

				if ($this->validation())
				{
					$this->array ['result'] = $this::getRating($groupId);

					if (!empty($this->array['result']))
					{
						foreach ($this->array['result'] as $item)
						{
							$this->array['points'][$item['std_id']][$item['sub_id']] = $item['points'];
							$this->array['students'][$item['std_id']] = $item['std_name'];
							$this->array['subjects'][$item['sub_id']] = $item['sub_name'];
						}

					} else
					{
						$this->errors [] = "Недостаточно данных для построения рейтинга!";
						$this->array ['errors'] = $this->errors;
					}
				} else
				{
					$this->array ['errors'] = $this->errors;
				}
			}
		}
		$this->view->render("rating", ['data' => $this->array]);
	}


	public static function getRating($groupID)
	{
		$db = DB::getInstance();

		$sql = "SELECT s.id as std_id, s.name as std_name, subjects.id as sub_id, subjects.name as sub_name,";
		$sql .= " marks.points as points FROM groups";
		$sql .= " JOIN students s ON s.`group` = `groups`.id ";
		$sql .= " JOIN subjectingroup sig ON sig.`group` = `groups`.id";
		$sql .= " JOIN subjects ON subjects.id = sig.subject";
		$sql .= " LEFT JOIN marks ON marks.subject = sig.subject and marks.student = s.id";
		$sql .= " WHERE `groups`.id = :group_id";

		$query = $db->getConnection()->prepare($sql);
		$query->bindValue(":group_id", $groupID, \PDO::PARAM_INT);
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * @return bool
	 */
	protected function validation(): bool
	{
		$count = 0;
		foreach ($_GET as $field => $value)
		{
			if (in_array($field, $this->fieldsToCheck))
			{
				if ($value == '' || ctype_space($value))
				{
					$this->errors [] = "Поле \"Группа\" пустое! Выберите группу!";
					$count++;
				}
			}
		}

		return $count == 0;
	}
}