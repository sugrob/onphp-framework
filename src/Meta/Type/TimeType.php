<?php
/***************************************************************************
 *   Copyright (C) 2006-2007 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

namespace OnPHP\Meta\Type;

use OnPHP\Core\Base\Time;
use OnPHP\Core\OSQL\DataType;

/**
 * @ingroup Types
**/
final class TimeType extends ObjectType
{
	public function getPrimitiveName()
	{
		return 'time';
	}

	public function isGeneric()
	{
		return true;
	}
	
	public function getFullClass() {
		return Time::class;
	}

	public function toColumnType()
	{
		return DataType::class.'::create('.DataType::class.'::TIME)';
	}
}
?>