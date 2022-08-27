<?php

namespace App\Tables;

use Core\MainModel;
use Core\DB\DB;

class Marks extends MainModel
{
	private array $fieldsTable = ['student', 'subject', 'points'];

	public array $inputFields = [
		[
			"key" => "group",
			"type" => "selector",
			"name" => "Группа",
			],
		[
			"key" => "student",
			"type" => "selector",
			"name" => "Студент",
			],
		[
			"key" => "subject",
			"type" => "selector",
			"name" => "Предмет",
			],
		[
			"key" => "points",
			"type" => "text",
			"name" => "Баллы",
			],
		];

	public array $namesColumns = [
		"id" => "id",
		"student" => "Студент(id)",
		"subject" => "Предмет(id)",
		"points" => "Баллы",
		];

	/**
	 * @return string
	 */
	protected static function getTableName(): string
	{
		return "marks";
	}

	/**
	 * @return array|string[]
	 */
	protected function getMap(): array
	{
		return $this->fieldsTable;
	}

	/**
	 * @return void
	 * @throws \Exception
	 */
	public function showTable()
	{
		$this->array['students'] = Students::getAll();
		$this->array['subjects'] = Subjects::getAll();
		parent::showTable();
	}

	public function addEntry($dataArray)
	{
		$this->array['groups'] = Groups::getAll();

		if (isset($dataArray['get']['group']))
		{
			$this->getSubjects($dataArray['get']['group'], $this->array['groups']);
		}
		parent::addEntry($dataArray);
	}

	/**
	 * @param $id
	 * @param $dataArray
	 * @return void
	 * @throws \Exception
	 */
	public function editEntry($id, $dataArray)
	{
		$this->array['groups'] = Groups::getAll();
		if (isset($dataArray['get']['group']))
		{
			$this->getSubjects($dataArray['get']['group'], $this->array['groups']);
		}
		parent::editEntry($id, $dataArray);
	}

	/**
	 * @param array $checkArr
	 * @return array
	 */
	public static function checkEntry(array $checkArr): array
	{
		$db = DB::getInstance();
		$query = $db->getConnection()->prepare("SELECT * FROM " . static::getTableName() . " WHERE student = :student and subject = :subject");
		$query->bindValue(":student", $checkArr['student'], \PDO::PARAM_INT);
		$query->bindValue(":subject", $checkArr['subject'], \PDO::PARAM_INT);
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	protected function getSortingResultArray($groupId)
	{
		$result = $this::getDataForGroup($groupId);
		if(!empty($result))
		{
			foreach ($result as $item)
			{
				$this->array['students'][$item['std_id']] = [
					"std_id" => $item['std_id'],
					"std_name" => $item['std_name'],
				];
				$this->array['subjects'][$item['sub_id']] = [
					"sub_id" => $item['sub_id'],
					"sub_name" => $item['sub_name'],
				];
			}
		}
	}

	protected static function getDataForGroup($groupId)
	{
		$db = DB::getInstance();

		$sql = "SELECT s.name as std_name, s.id as std_id, sub.name as sub_name, sub.id as sub_id FROM `groups` ";
		$sql .= "JOIN students s on `groups`.id = s.`group` ";
        $sql .= "JOIN subjectingroup sig on `groups`.id = sig.`group` ";
        $sql .= "JOIN subjects sub on sig.subject = sub.id ";
		$sql .= "WHERE `groups`.id = :group";

		$query = $db->getConnection()->prepare($sql);
		$query->bindValue(":group", $groupId, \PDO::PARAM_INT);
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Метод простейшей валидации полей ввода
	 * @return bool
	 */
	protected function validation($dataArray): bool
	{
		$count = 0;

		$checkArray = $this::checkEntry($dataArray['fields']);
		foreach ($dataArray['fields'] as $field => $value)
		{
			if (in_array($field, $this->fieldsTable))
			{
				if ($value == '' || ctype_space($value))
				{
					if ($field == "student")
					{
						$this->errors [] = "Поле \"Студент\" не может быть пустым! Выберите студента!";
						$count++;
					}
					if ($field == "subject")
					{
						$this->errors [] = "Поле \"Предмет\" не может быть пустым! Выберите предмет!";
						$count++;
					}
				}
				if ($field == "points")
				{
					if (!is_numeric($value))
					{
						$this->errors [] = "Поле \"Баллы\" может иметь только числовое значение!";
						$count++;
					} elseif ($value < 0 || $value > 100)
					{
						$this->errors [] = "Вы не можете поставить меньше 0 или больше 100 баллов!!!";
						$count++;
					}
				}
			}
		}
		return $count == 0 && $this->checkForDuplication($checkArray, $dataArray['fields']);
	}

	/**
	 * @param $checkArray
	 * @param $dataForCheck
	 * @return bool
	 */
	protected function checkForDuplication($checkArray, $dataForCheck = null): bool
	{
		if (count($checkArray) == 0)
		{
			foreach ($this->array['subjects'] as $subject)
			{
				if ($dataForCheck['subject'] == $subject['sub_id'])
				{
					return true;
				}
			}

			$this->errors [] = "Выбранного предмета не существует! Возможно он был удален из группы ранее.";
			return false;
		} elseif (isset($this->array['selectedItem']))
		{
			if ($checkArray['0']['id'] == $this->array['selectedItem']['id'])
			{
				foreach ($this->array['subjects'] as $subject)
				{
					if ($dataForCheck['subject'] == $subject['sub_id'])
					{
						return true;
					}
				}

				$this->errors [] = "Выбранного предмета не существует! Возможно он был удален из группы ранее.";
				return false;
			} else
			{
				$this->errors [] = "Нельзя повторно выставить оценку по одному и тому же предмету!";
				return false;
			}
		} else
		{
			$this->errors [] = "Нельзя повторно выставить оценку по одному и тому же предмету!";
			return false;
		}
	}


	/**
	 * @param $groupId
	 * @param array $groups
	 * @return void
	 */
	private function getSubjects($groupId, array $groups)
	{
		if ($groupId != '')
		{
			foreach ($groups as $group)
			{
				if ($groupId == $group['id'])
				{
					$this->getSortingResultArray($groupId);
					return;
				}
			}

			$this->errors [] = "Выбранной группы не существует! Возможно она была удалена ранее.";
			$this->array ['errors'] = $this->errors;
		} else
		{
			$this->errors [] = "Поле \"Группа\" не может быть пустым! Выберите группу!";
			$this->array ['errors'] = $this->errors;
		}
	}

}