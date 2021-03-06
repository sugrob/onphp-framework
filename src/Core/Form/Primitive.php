<?php
/****************************************************************************
 *   Copyright (C) 2004-2009 by Konstantin V. Arkhipov, Anton E. Lebedevich *
 *                                                                          *
 *   This program is free software; you can redistribute it and/or modify   *
 *   it under the terms of the GNU Lesser General Public License as         *
 *   published by the Free Software Foundation; either version 3 of the     *
 *   License, or (at your option) any later version.                        *
 *                                                                          *
 ****************************************************************************/

namespace OnPHP\Core\Form;

use OnPHP\Core\Base\Assert;
use OnPHP\Core\Base\Prototyped;
use OnPHP\Core\Base\StaticFactory;
use OnPHP\Core\Form\Primitives\BasePrimitive;
use OnPHP\Core\Form\Primitives\ExplodedPrimitive;
use OnPHP\Core\Form\Primitives\PrimitiveAlias;
use OnPHP\Core\Form\Primitives\PrimitiveAnyType;
use OnPHP\Core\Form\Primitives\PrimitiveArray;
use OnPHP\Core\Form\Primitives\PrimitiveBinary;
use OnPHP\Core\Form\Primitives\PrimitiveBoolean;
use OnPHP\Core\Form\Primitives\PrimitiveClass;
use OnPHP\Core\Form\Primitives\PrimitiveDate;
use OnPHP\Core\Form\Primitives\PrimitiveDateRange;
use OnPHP\Core\Form\Primitives\PrimitiveEnum;
use OnPHP\Core\Form\Primitives\PrimitiveEnumByValue;
use OnPHP\Core\Form\Primitives\PrimitiveEnumeration;
use OnPHP\Core\Form\Primitives\PrimitiveEnumerationByValue;
use OnPHP\Core\Form\Primitives\PrimitiveEnumerationList;
use OnPHP\Core\Form\Primitives\PrimitiveEnumList;
use OnPHP\Core\Form\Primitives\PrimitiveFile;
use OnPHP\Core\Form\Primitives\PrimitiveFloat;
use OnPHP\Core\Form\Primitives\PrimitiveForm;
use OnPHP\Core\Form\Primitives\PrimitiveFormsList;
use OnPHP\Core\Form\Primitives\PrimitiveHstore;
use OnPHP\Core\Form\Primitives\PrimitiveHttpUrl;
use OnPHP\Core\Form\Primitives\PrimitiveIdentifier;
use OnPHP\Core\Form\Primitives\PrimitiveIdentifierList;
use OnPHP\Core\Form\Primitives\PrimitiveImage;
use OnPHP\Core\Form\Primitives\PrimitiveInet;
use OnPHP\Core\Form\Primitives\PrimitiveInteger;
use OnPHP\Core\Form\Primitives\PrimitiveIntegerIdentifier;
use OnPHP\Core\Form\Primitives\PrimitiveIpAddress;
use OnPHP\Core\Form\Primitives\PrimitiveIpRange;
use OnPHP\Core\Form\Primitives\PrimitiveList;
use OnPHP\Core\Form\Primitives\PrimitiveMultiList;
use OnPHP\Core\Form\Primitives\PrimitiveNoValue;
use OnPHP\Core\Form\Primitives\PrimitivePlainList;
use OnPHP\Core\Form\Primitives\PrimitivePolymorphicIdentifier;
use OnPHP\Core\Form\Primitives\PrimitiveRange;
use OnPHP\Core\Form\Primitives\PrimitiveScalarIdentifier;
use OnPHP\Core\Form\Primitives\PrimitiveString;
use OnPHP\Core\Form\Primitives\PrimitiveTernary;
use OnPHP\Core\Form\Primitives\PrimitiveTime;
use OnPHP\Core\Form\Primitives\PrimitiveTimestamp;
use OnPHP\Core\Form\Primitives\PrimitiveTimestampRange;
use OnPHP\Core\Form\Primitives\PrimitiveTimestampTZ;
use OnPHP\Main\DAO\DAOConnected;

/**
 * Factory for various Primitives.
 *
 * @ingroup Form
**/
final class Primitive extends StaticFactory
{
	/**
	 * @return BasePrimitive
	**/
	public static function spawn($primitive, $name)
	{
		Assert::classExists($primitive);

		return new $primitive($name);
	}

	/**
	 * @return Primitive
	**/
	public static function alias($name, BasePrimitive $prm)
	{
		return new PrimitiveAlias($name, $prm);
	}

	/**
	 * @return PrimitiveAnyType
	**/
	public static function anyType($name)
	{
		return new PrimitiveAnyType($name);
	}

	/**
	 * @return PrimitiveInteger
	**/
	public static function integer($name)
	{
		return new PrimitiveInteger($name);
	}

	/**
	 * @return PrimitiveFloat
	**/
	public static function float($name)
	{
		return new PrimitiveFloat($name);
	}

	/**
	 * @return PrimitiveIdentifier
	 * @obsoleted by integerIdentifier and scalarIdentifier
	**/
	public static function identifier($name)
	{
		return new PrimitiveIdentifier($name);
	}

	/**
	 * @return PrimitiveIntegerIdentifier
	**/
	public static function integerIdentifier($name)
	{
		return new PrimitiveIntegerIdentifier($name);
	}

	/**
	 * @return PrimitiveScalarIdentifier
	**/
	public static function scalarIdentifier($name)
	{
		return new PrimitiveScalarIdentifier($name);
	}

	/**
	 * @return PrimitivePolymorphicIdentifier
	**/
	public static function polymorphicIdentifier($name)
	{
		return new PrimitivePolymorphicIdentifier($name);
	}

	/**
	 * @return PrimitiveIdentifierList
	**/
	public static function identifierlist($name)
	{
		return new PrimitiveIdentifierList($name);
	}

	/**
	 * @return PrimitiveClass
	**/
	public static function clazz($name)
	{
		return new PrimitiveClass($name);
	}

	/**
	 * @return PrimitiveEnumeration
	**/
	public static function enumeration($name)
	{
		return new PrimitiveEnumeration($name);
	}

	/**
	 * @return PrimitiveEnumerationByValue
	**/
	public static function enumerationByValue($name)
	{
		return new PrimitiveEnumerationByValue($name);
	}

	/**
	 * @return PrimitiveEnumerationList
	**/
	public static function enumerationList($name)
	{
		return new PrimitiveEnumerationList($name);
	}

	/**
	 * @return PrimitiveDate
	**/
	public static function date($name)
	{
		return new PrimitiveDate($name);
	}

	/**
	 * @return PrimitiveTimestamp
	**/
	public static function timestamp($name)
	{
		return new PrimitiveTimestamp($name);
	}

	/**
	 * @return PrimitiveTimestampTZ
	**/
	public static function timestampTZ($name)
	{
		return new PrimitiveTimestampTZ($name);
	}

	/**
	 * @return PrimitiveTime
	**/
	public static function time($name)
	{
		return new PrimitiveTime($name);
	}

	/**
	 * @return PrimitiveString
	**/
	public static function string($name)
	{
		return new PrimitiveString($name);
	}

	/**
	 * @return PrimitiveBinary
	**/
	public static function binary($name)
	{
		return new PrimitiveBinary($name);
	}

	/**
	 * @return PrimitiveRange
	**/
	public static function range($name)
	{
		return new PrimitiveRange($name);
	}

	/**
	 * @return PrimitiveDateRange
	**/
	public static function dateRange($name)
	{
		return new PrimitiveDateRange($name);
	}

	/**
	 * @return PrimitiveTimestampRange
	**/
	public static function timestampRange($name)
	{
		return new PrimitiveTimestampRange($name);
	}

	/**
	 * @return PrimitiveList
	**/
	public static function choice($name)
	{
		return new PrimitiveList($name);
	}

	/**
	 * @return PrimitiveArray
	**/
	public static function set($name)
	{
		return new PrimitiveArray($name);
	}

	/**
	 * @return PrimitiveHstore
	**/
	public static function hstore($name)
	{
		return new PrimitiveHstore($name);
	}

	/**
	 * @return PrimitiveMultiList
	**/
	public static function multiChoice($name)
	{
		return new PrimitiveMultiList($name);
	}

	/**
	 * @return PrimitivePlainList
	**/
	public static function plainChoice($name)
	{
		return new PrimitivePlainList($name);
	}

	/**
	 * @return PrimitiveBoolean
	**/
	public static function boolean($name)
	{
		return new PrimitiveBoolean($name);
	}

	/**
	 * @return PrimitiveTernary
	**/
	public static function ternary($name)
	{
		return new PrimitiveTernary($name);
	}

	/**
	 * @return PrimitiveFile
	**/
	public static function file($name)
	{
		return new PrimitiveFile($name);
	}

	/**
	 * @return PrimitiveImage
	**/
	public static function image($name)
	{
		return new PrimitiveImage($name);
	}

	/**
	 * @return ExplodedPrimitive
	**/
	public static function exploded($name)
	{
		return new ExplodedPrimitive($name);
	}

	/**
	 * @return PrimitiveInet
	**/
	public static function inet($name)
	{
		return new PrimitiveInet($name);
	}

	/**
	 * @return PrimitiveForm
	**/
	public static function form($name)
	{
		return new PrimitiveForm($name);
	}

	/**
	 * @return PrimitiveFormsList
	**/
	public static function formsList($name)
	{
		return new PrimitiveFormsList($name);
	}

	/**
	 * @return PrimitiveNoValue
	**/
	public static function noValue($name)
	{
		return new PrimitiveNoValue($name);
	}

	/**
	 * @return PrimitiveHttpUrl
	**/
	public static function httpUrl($name)
	{
		return new PrimitiveHttpUrl($name);
	}

	/**
	 * @return BasePrimitive
	**/
	public static function prototyped($class, $propertyName, $name = null)
	{
		Assert::isInstance($class, Prototyped::class);

		$proto = is_string($class)
			? call_user_func(array($class, 'proto'))
			: $class->proto();

		if (!$name)
			$name = $propertyName;

		return $proto->getPropertyByName($propertyName)->
			makePrimitive($name);
	}

	/**
	 * @return PrimitiveIdentifier
	**/
	public static function prototypedIdentifier($class, $name = null)
	{
		Assert::isInstance($class, DAOConnected::class);

		$dao = is_string($class)
			? call_user_func(array($class, 'dao'))
			: $class->dao();

		return self::prototyped($class, $dao->getIdName(), $name);
	}

	/**
	 * @return PrimitiveIpAddress
	**/
	public static function ipAddress($name)
	{
		return new PrimitiveIpAddress($name);
	}

	/**
	 * @return PrimitiveIpRange
	 */
	public static function ipRange($name)
	{
		return new PrimitiveIpRange($name);
	}

	/**
	 * @return PrimitiveEnum
	**/
	public static function enum($name)
	{
		return new PrimitiveEnum($name);
	}

	/**
	 * @return PrimitiveEnumByValue
	**/
	public static function enumByValue($name)
	{
		return new PrimitiveEnumByValue($name);
	}

	/**
	 * @return PrimitiveEnumList
	**/
	public static function enumList($name)
	{
		return new PrimitiveEnumList($name);
	}
}
?>