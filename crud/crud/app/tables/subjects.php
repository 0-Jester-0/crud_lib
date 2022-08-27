<?php

namespace App\Tables;

use Core\DB\DB;
use Core\MainModel;

class Subjects extends MainModel
{
	private array $fieldsTable = ['name'];
	public array $inputFields = [
		[
			"key" => "name",
			"type" => "text",
			"name" => "Название предмета",
		]
	];

	public array $namesColumns = [
		"id" => "id",
		"name" => "Название",
	];
	/**
	 * @return string
	 */
	protected static function getTableName(): string
	{
		return "subjects";
	}

	/**
	 * @return array|string[]
	 */
	protected function getMap(): array
	{
		return $this->fieldsTable;
	}

	/**
	 * Метод простейшей валидации полей ввода
	 * @param array $checkArr
	 * @return array|false
	 */
	public static function checkEntry(array $checkArr)
	{
		$db = DB::getInstance();
		$query = $db->getConnection()->prepare("SELECT * FROM " . static::getTableName() . " WHERE `name` = :name");
		$query->bindValue(":name", $checkArr['name']);
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
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
					if ($field == "name")
					{
						$this->errors [] = "Поле \"Название предмета\" не может быть пустым! Введите название предмета!";
						$count++;
					}
				}
			}
		}
		return $count == 0 && $this->checkForDuplication($checkArray);
	}
}