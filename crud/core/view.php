<?php


namespace Core;


class View
{
	/**
	 * @param $actionName
	 * @param array $data
	 * @return void
	 * @throws \Exception
	 */
	public function render($actionName, array $data = [])
	{
		extract($data);

		$file =  $_SERVER['DOCUMENT_ROOT'] . "/crud/app/view/templates/inc/" . $actionName . ".php";;

		if (is_readable($file))
		{
			require $file;
		} else
		{
			throw new \Exception("$file not found!");
		}
	}
}