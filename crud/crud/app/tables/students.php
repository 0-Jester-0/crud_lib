<?php

namespace App\Tables;

use Core\DB\DB;
use Core\MainModel;

class Students extends MainModel
{
	private array $fieldsTable = ['name', 'group'];
	public array $inputFields = [
		 [
			"key" => "name",
			"type" => "text",
			"name" => "ФИО Студента",
		],
		[
			"key" => "group",
			"type" => "selector",
			"name" => "Группа",
		],
	];

	public array $namesColumns = [
		"id" => "id",
		"name" => "Имя",
		"group" => "Группа(id)",
	];

	/**
	 * @return string
	 */
	protected static function getTableName(): string
	{
		return "students";
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
		parent::editEntry($id, $dataArray);
	}

	/**
	 * Метод простейшей валидации полей ввода
	 * @return bool
	 */
	protected function validation($dataArray): bool
	{
		$count = 0;
		foreach ($dataArray['fields'] as $field => $value)
		{
			if (in_array($field, $this->fieldsTable))
			{
				if ($value == '' || ctype_space($value))
				{
					if ($field == "name")
					{
						$this->errors [] = "Поле \"ФИО Студента\" не может быть пустым! Введите имя студента!";
						$count++;
					}
					if ($field == "group")
					{
						$this->errors [] = "Поле \"Группа\" не может быть пустым! Выберите группу!";
						$count++;
					}
				}
			}
		}
		return $count == 0;
	}
}