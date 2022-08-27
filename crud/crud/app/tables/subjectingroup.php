<?php

namespace App\Tables;

use Core\MainModel;
use Core\DB\DB;

class SubjectInGroup extends MainModel
{
	private array $fieldsTable = ['group', 'subject'];
	public array $inputFields = [
		[
			"key" => "group",
			"type" => "selector",
			"name" => "Группа",
		],
		[
			"key" => "subject",
			"type" => "selector",
			"name" => "Предмет",
		],
	];

	public array $namesColumns = [
		"id" => "id",
		"group" => "Группа(id)",
		"subject" => "Предмет(id)",
	];

	/**
	 * @return string
	 */
	protected static function getTableName(): string
	{
		return "subjectingroup";
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
		$this->array['groups'] = Groups::getAll();
		$this->array['subjects'] = Subjects::getAll();
		parent::showTable();
	}

	/**
	 * @param $dataArray
	 * @return void
	 * @throws \Exception
	 */
	public function addEntry($dataArray)
	{
		$this->array['groups'] = Groups::getAll();
		$this->array['subjects'] = Subjects::getAll();
		parent::addEntry($dataArray);
	}

	/**
	 * @param $id
	 * @return array|void
	 * @throws \Exception
	 */
	public function editEntry($id, $dataArray)
	{
		$this->array['groups'] = Groups::getAll();
		$this->array['subjects'] = Subjects::getAll();
		parent::editEntry($id, $dataArray);
	}

	public function deleteEntry($id)
	{
		$marks = Marks::getAll();
		$students = Students::getAll();
		$deletedEntry = $this->getById($id);
		$count = 0;
		if (!empty($deletedEntry))
		{
			foreach ($marks as $mark)
			{
				foreach ($students as $student)
				{
					if ($mark['subject'] == $deletedEntry['subject'] && $student['group'] == $deletedEntry['group'])
					{
						if ($student['id'] == $mark['student'])
						{
							$count++;
						}
					}
				}
			}

			if ($count == 0)
			{
				$this->delete($id);
			} else
			{
				throw new \Exception("Нельзя удалить предмет из группы пока по нему есть оценка(и).");
			}
		} else
		{
			throw new \Exception("Ошибка удаления! Возможно запись уже была удалена ранее!");
		}
	}

	/**
	 * @param array $checkArr
	 * @return array|false
	 */
	public static function checkEntry(array $checkArr)
	{
		$db = DB::getInstance();
		$query = $db->getConnection()->prepare("SELECT * FROM " . static::getTableName() . " WHERE `group` = :group AND subject = :subject");
		$query->bindValue(":group", $checkArr['group'], \PDO::PARAM_INT);
		$query->bindValue(":subject", $checkArr['subject'], \PDO::PARAM_INT);
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Метод валидации и проверок
	 * @param $dataArray
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
					if ($field == "group")
					{
						$this->errors [] = "Поле \"Группа\" не может быть пустым! Выберите группу!";
						$count++;
					}
					if ($field == "subject")
					{
						$this->errors [] = "Поле \"Предмет\" не может быть пустым! Выберите предмет!";
						$count++;
					}
				}
			}
		}
		return $count == 0 && $this->checkForDuplication($checkArray);
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
			return true;
		} elseif (isset($this->array['selectedItem']))
		{
			if ($checkArray['0']['id'] == $this->array['selectedItem']['id'])
			{
				return true;
			} else
			{
				$this->errors [] = "Нельзя добавить предмет в группу, в которой он уже есть!";
				return false;
			}
		} else
		{
			$this->errors [] = "Нельзя добавить предмет в группу, в которой он уже есть!";
			return false;
		}
	}
}