<?php

namespace Core;

use Core\DB\DB;

abstract class MainModel
{
	private array $fieldsTable = [];
	public array $inputFields = [];
	public array $namesColumns = [];

	protected object $controller;

	public array $array = [];
	public array $errors = [];

	abstract protected function getMap();

	/**
	 * @throws \Exception
	 */
	protected static function getTableName(): string
	{
		throw new \Exception("Error getting table name!");
	}

	public function __construct()
	{
		$this->controller = new MainController();
	}

	/**
	 *  Метод просмотра всех записей таблиц
	 * @return void
	 * @throws \Exception
	 */
	public function showTable()
	{
		$this->array ['namesColumns'] = $this->namesColumns;
		$this->array ['page'] = ucfirst(static::getTableName());
		$this->array ['pageTitle'] = ucfirst(static::getTableName());
		$this->array ['result'] = $this::getAll();
		$this->controller->chooseAction(static::getTableName(), "show", $this->array);
	}

	/**
	 * @param $dataArray
	 * @return void
	 * @throws \Exception
	 */
	public function addEntry($dataArray)
	{
		$this->array ['inputFields'] = $this->inputFields;
		$this->array ['namesColumns'] = $this->namesColumns;
		$this->array ['page'] = ucfirst(static::getTableName());
		$this->array ['pageTitle'] = ucfirst(static::getTableName()) . "Add";

		if (isset($dataArray['fields']))
		{
			if ($this->validation($dataArray))
			{
				$newEntry = $dataArray['fields'];

				$this->insert($newEntry);
			} else
			{
				$this->array ['errors'] = $this->errors;
				$this->controller->chooseAction(static::getTableName(), "add", $this->array);
				exit();
			}
		} else
		{
			$this->controller->chooseAction(static::getTableName(), "add", $this->array);
			exit();
		}
	}

	/**
	 * Метод редактирования записи таблиц
	 * @param $id
	 * @param $dataArray
	 * @return void
	 * @throws \Exception
	 */
	public function editEntry($id, $dataArray)
	{
		$this->array ['inputFields'] = $this->inputFields;
		$this->array ['page'] = ucfirst(static::getTableName());
		$this->array['selectedItem'] = $this::getById($id);
		$this->array ['pageTitle'] = ucfirst(static::getTableName()) . "Edit";

		if (isset($dataArray['fields']))
		{
			if ($this->validation($dataArray))
			{
				$changedData = $dataArray['fields'];
				$changedData ['id'] = $id;

				$this->update($changedData);
			} else
			{
				$this->array ['errors'] = $this->errors;
				$this->controller->chooseAction($this->getTableName(), "edit", $this->array);
				exit();
			}
		} else
		{
			$this->controller->chooseAction($this->getTableName(), "edit", $this->array);
			exit();
		}
	}

	/**
	 * @param $id
	 * @throws \Exception
	 */
	public function deleteEntry($id)
	{
		if ($this->getById($id))
		{
			$this->delete($id);
		} else
		{
			throw new \Exception("Ошибка удаления возможно запись уже была удалена ранее!");
		}
	}

	/**
	 * Блок SQL запросов
	 */

	/**
	 * @return array
	 * @throws \Exception
	 */
	public static function getAll(): ?array
	{
		$db = DB::getInstance();
		$query = $db->getConnection()->prepare("SELECT * FROM " . static::getTableName() . " ORDER BY id ASC");
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * @param array $newData
	 * @throws \Exception
	 */
	protected function insert(array $newData)
	{
		$db = DB::getInstance();
		$query = $db->getConnection()->prepare("INSERT INTO " . static::getTableName() . " (" . $this->getFieldsFromQuery() . ") VALUE (" . $this->getFieldsFromValue() . ")");
		if (!$query->execute($newData))
		{
			throw new \Exception("Ошибка добавления записи!");
		}
	}

	/**
	 * @param $id
	 * @param array $changedData
	 * @throws \Exception
	 */
	protected function update(array $changedData)
	{
		$parameters = explode(", ", $this->getFieldsFromValue());
		$db = DB::getInstance();
		$query = $db->getConnection()->prepare("UPDATE " . static::getTableName() . " SET " . $this->getFieldsFromQueryUpdate() . " WHERE id = :id");
		if (!$query->execute($changedData))
		{
			throw new \Exception("Ошибка изменения записи!");
		}
	}

	/**
	 * @param $id
	 * @return void
	 * @throws \Exception
	 */
	protected function delete($id)
	{
		$db = DB::getInstance();
		$query = $db->getConnection()->prepare("DELETE FROM " . static::getTableName() . " WHERE id = :id");
		$query->bindValue(":id", $id, \PDO::PARAM_INT);
		if (!$query->execute())
		{
			throw new \Exception("Ошибка удаления записи! Возможно есть записи связанные с этой таблицей!");
		}
	}

	/**
	 * @param $id
	 * @return mixed
	 * @throws \Exception
	 */
	protected function getById($id)
	{
		$db = DB::getInstance();
		$query = $db->getConnection()->prepare("SELECT * FROM " . static::getTableName() . " WHERE id = :id");
		$query->bindValue(":id", $id, \PDO::PARAM_INT);
		$query->execute();
		return $query->fetch(\PDO::FETCH_ASSOC);
	}


	/**
	 * Блок генерации строк для SQL запросов
	 */

	/**
	 * @return string
	 */
	protected function getFieldsFromQuery(): string
	{
		$fieldsTable = $this->getMap();
		$newFieldsTable = [];
		foreach ($fieldsTable as $field)
		{
			$newFieldsTable [] = preg_replace("~$field~", "`$field`", $field);
		}
		return implode(", ", $newFieldsTable);
	}

	/**
	 * @return string
	 */
	protected function getFieldsFromValue(): string
	{
		$fieldsTable = $this->getMap();
		$newFieldsTable = [];
		foreach ($fieldsTable as $field)
		{
			$newFieldsTable [] = preg_replace("~$field~", ":" . $field, $field);
		}
		return implode(", ", $newFieldsTable);
	}

	/**
	 * @return string
	 */
	protected function getFieldsFromQueryUpdate(): string
	{
		$fieldsTable = $this->getMap();
		$newFieldsTable = [];
		foreach ($fieldsTable as $field) {
			$newFieldsTable [] = preg_replace("~$field~", "`$field`=:$field", $field);
		}
		return implode(", ", $newFieldsTable);
	}

	//Валидация
	abstract protected function validation($dataArray);

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
				$this->errors [] = "Ошибка добавления записи! Такая запись уже существует.";
				return false;
			}
		} else
		{
			$this->errors [] = "Ошибка добавления записи! Такая запись уже существует.";
			return false;
		}
	}
}