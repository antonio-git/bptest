<?php

namespace Bitrix\Sale\Exchange;


use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\NotImplementedException;
use Bitrix\Sale\Exchange\Internals\ExchangeLogTable;
use Bitrix\Sale\Exchange\Internals\LoggerDiag;

abstract class ManagerBase
{
	const EXCHANGE_DIRECTION_IMPORT = 'I';
	const EXCHANGE_DIRECTION_EXPORT = 'E';

	protected static $instance = null;
	/** @var ISettingsImport|ISettingsExport $settings */
	protected $settings = null;
	/** @var LoggerDiag $logger */
	protected $logger = null;

	/**
	 * @return string
	 */
	static public function getDirectionType()
	{
		throw new NotImplementedException('The method is not implemented.');
	}

	/**
	 * @return static
	 */
	private static function getInstance()
	{
		if(self::$instance === null)
		{
			self::$instance = new static();
		}
		return self::$instance;
	}

	/**
	 * Get import by Type ID.
	 * @param $typeId
	 * @return null|static
	 * @throws ArgumentOutOfRangeException
	 */
	static protected function getImportByType($typeId)
	{
		static::IsDefinedTypeId($typeId);

		$import = static::getInstance();
		return isset($import[$typeId]) ? $import[$typeId] : null;
	}

	/**
	 * @param $typeId
	 * @return ImportBase
	 */
	static public function create($typeId)
	{
		throw new NotImplementedException('The method is not implemented.');
	}

	static public function deleteLoggingDate()
	{
		ExchangeLogTable::deleteOldRecords(static::getDirectionType());
	}

	static protected function IsDefinedTypeId($typeId)
	{
		if(!is_int($typeId))
		{
			$typeId = (int)$typeId;
		}

		if(!EntityType::IsDefined($typeId))
		{
			throw new ArgumentOutOfRangeException('Is not defined', EntityType::FIRST, EntityType::LAST);
		}
	}
}