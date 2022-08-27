<?php

namespace Core;

use Core\View;

class MainController
{
	protected object $view;

	public array $array = [];
	public array $errors = [];

	public function __construct()
	{
		$this->view = new View();
	}

	/**
	 * @param $className
	 * @return void
	 */
	public function show($className)
	{
		$modelObject = new $className();
		$modelObject->showTable();
	}

	/**
	 * @param $className
	 * @return void
	 */
	public function add($className)
	{
		$modelObject = new $className();

		$arrayParts = explode("\\", $className);

		"POST" == $_SERVER["REQUEST_METHOD"] ? $dataArray['fields'] = $_POST : $dataArray = [];
		if (isset($_GET['group']) || "GET" == $_SERVER['REQUEST_METHOD'])
		{
			$dataArray['get'] = $_GET;
		}

		try
		{
			$modelObject->addEntry($dataArray);
		} catch (\Exception $exception)
		{
			session_start();
			$_SESSION['error'] = $exception->getMessage();
			header("Location: /" . lcfirst(array_pop($arrayParts)));
			die();
		}

		header("Location: /" . lcfirst(array_pop($arrayParts)));
		die();
	}

	/**
	 * @param $className
	 * @param $id
	 * @return void
	 */
	public function edit($className, $id)
	{
		$modelObject = new $className();

		$arrayParts = explode("\\", $className);

		"POST" == $_SERVER["REQUEST_METHOD"] ? $dataArray['fields'] = $_POST : $dataArray = [];
		if (isset($_GET['group']) || "GET" == $_SERVER['REQUEST_METHOD'])
		{
			$dataArray['get'] = $_GET;
		}
		try
		{
			$modelObject->editEntry($id, $dataArray);
		} catch (\Exception $exception)
		{
			session_start();
			$_SESSION['error'] = $exception->getMessage();
			header("Location: /" . lcfirst(array_pop($arrayParts)));
			die();
		}

		header("Location: /" . lcfirst(array_pop($arrayParts)));
		die();
	}

	/**
	 * @param $className
	 * @param $id
	 * @return void
	 */
	public function remove($className, $id)
	{
		$modelObject = new $className();

		$arrayParts = explode("\\", $className);
		try
		{
			$modelObject->deleteEntry($id);
		} catch (\Exception $exception)
		{
			session_start();
			$_SESSION['error'] = $exception->getMessage();
			header("Location: /" . lcfirst(array_pop($arrayParts)));
			die();
		}

		header("Location: /" . lcfirst(array_pop($arrayParts)));
		die();
	}

	/**
	 * @throws \Exception
	 */
	public function chooseAction($table, $actionName, $dataArray)
	{
		switch ($actionName)
		{
			case "show":
				$this->view->render("show", ['data' => $dataArray]);
				break;
			case "add":
				if ($table != "marks")
				{
					$this->view->render("add", ['data' => $dataArray]);
				} else
				{
					$this->view->render("addmarks", ['data' => $dataArray]);
				}
				break;
			case "edit":
				if ($table != "marks")
				{
					$this->view->render("edit", ['data' => $dataArray]);
				} else
				{
					$this->view->render("editmarks", ['data' => $dataArray]);
				}
				break;
		}
	}


}