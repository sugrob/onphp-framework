<?php
/***************************************************************************
 *   Copyright (C) 2006-2007 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	/**
	 * @ingroup Builders
	**/
	final class AutoClassBuilder extends BaseBuilder
	{
		public static function build(MetaClass $class)
		{
			$out = self::getHead();
			
			$out .= "abstract class Auto{$class->getName()}";
			
			$isNamed = false;
			
			if ($parent = $class->getParent())
				$out .= " extends {$parent->getName()}";
			elseif (
				$class->getPattern() instanceof DictionaryClassPattern
				&& $class->hasProperty('name')
			) {
				$out .= " extends NamedObject";
				$isNamed = true;
			} elseif (!$class->getPattern() instanceof ValueObjectPattern)
				$out .= " extends IdentifiableObject";
			
			if ($interfaces = $class->getInterfaces())
				$out .= ' implements '.implode(', ', $interfaces);
			
			$out .= "\n{\n";
			
			foreach ($class->getProperties() as $property) {
				
				if ($isNamed && $property->getName() == 'name')
					continue;
				
				if ($property->getName() == 'id' && !$parent)
					continue;
				
				$out .=
					"protected \${$property->getName()} = "
					."{$property->getType()->getDeclaration()};\n";
				
				if ($property->getRelationId() == MetaRelation::LAZY_ONE_TO_ONE) {
					$out .= 
						"protected \${$property->getName()}Id = null;\n";
				}
			}
			
			$valueObjects = array();
			
			foreach ($class->getProperties() as $property) {
				if (
					$property->getType() instanceof ObjectType
					&& !$property->getType()->isGeneric()
					&& $property->getType()->getClass()->getPattern()
						instanceof ValueObjectPattern
				) {
					$valueObjects[$property->getName()] =
						$property->getType()->getClassName();
				}
			}
			
			if ($valueObjects) {
				$out .= <<<EOT

public function __construct()
{

EOT;
				foreach ($valueObjects as $propertyName => $className) {
					$out .= "\$this->{$propertyName} = new {$className}();\n";
				}
				
				$out .= "}\n";
			}
			
			$out .= self::buildSerializers($class);
			
			foreach ($class->getProperties() as $property) {
				
				if ($isNamed && $property->getName() == 'name')
					continue;
				
				if ($property->getName() == 'id' && !$parent)
					continue;
				
				$out .= $property->toMethods($class);
			}
			
			$out .= "}\n";
			$out .= self::getHeel();
			
			return $out;
		}
		
		private static function buildSerializers(MetaClass $class)
		{
			$slackers = array();
			
			foreach ($class->getProperties() as $property) {
				if ($property->getRelationId() == MetaRelation::LAZY_ONE_TO_ONE) {
					$slackers[] = $property;
				}
			}
			
			if (!$slackers)
				return null;
			
			$out = <<<EOT

public function __sleep()
{
	\$properties = get_object_vars(\$this);
	
	unset(

EOT;
			$unsetters = array();
			
			foreach ($slackers as $property) {
				$unsetters[] = "\$properties['{$property->getName()}']";
			}
			
			$out .= implode(",\n", $unsetters);
			
			$out .= <<<EOT

	);
	
	return array_keys(\$properties);
}

EOT;
			return $out;
		}
	}
?>