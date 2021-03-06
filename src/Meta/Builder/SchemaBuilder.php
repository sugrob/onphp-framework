<?php
/***************************************************************************
 *   Copyright (C) 2006-2008 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

namespace OnPHP\Meta\Builder;

use OnPHP\Core\OSQL\DBSchema;
use OnPHP\Core\OSQL\DBTable;
use OnPHP\Core\OSQL\ForeignChangeAction;
use OnPHP\Meta\Entity\MetaClass;
use OnPHP\Meta\Entity\MetaRelation;

/**
 * @ingroup Builders
**/
final class SchemaBuilder extends BaseBuilder
{
	public static function buildTable($tableName, array $propertyList)
	{
		$out = "\$schema->\n"
			."\taddTable(\n"
			."\t\t".DBTable::class."::create('{$tableName}')->";

		$columns = array();
		
		foreach ($propertyList as $property) {
			if (
				$property->getRelation()
				&& ($property->getRelationId() != MetaRelation::ONE_TO_ONE)
			) {
				continue;
			}
			
			$column = $property->toColumn();
			
			if (is_array($column)) {
				$columns = array_merge($columns, $column);
			} else {
				$columns[] = $property->toColumn();
			}
		}
		
		$out .= implode("->\n", $columns);
		
		return $out."\n);\n\n";
	}
		
	public static function buildRelations(MetaClass $class)
	{
		$out = null;
		
		$knownJunctions = array();
		
		foreach ($class->getAllProperties() as $property) {
			if ($relation = $property->getRelation()) {
				
				$foreignClass = $property->getType()->getClass();
				
				if (
					$relation->getId() == MetaRelation::ONE_TO_MANY
					// nothing to build, it's in the same table
					// or table does not exist at all
					|| !$foreignClass->getPattern()->tableExists()
					// no need to process them
					|| $class->getParent()
				) {
					continue;
				} elseif (
					$relation->getId() == MetaRelation::MANY_TO_MANY
				) {
					$tableName =
						$class->getTableName()
						.'_'
						.$foreignClass->getTableName();
					
					if (isset($knownJunctions[$tableName])) {
						continue; // collision prevention
					} else {
						$knownJunctions[$tableName] = true;
					}
						
					$foreignPropery = clone $foreignClass->getIdentifier();
					
					$name = $class->getName();
					$name = strtolower($name[0]).substr($name, 1);
					$name .= 'Id';
					
					$foreignPropery->
						setName($name)->
						setColumnName($foreignPropery->getConvertedName())->
						// we don't need primary key here
						setIdentifier(false);
					
					// we don't want any garbage in such tables
					$property = clone $property;
					$property->required();
					
					// prevent name collisions
					if (
						$property->getRelationColumnName()
						== $foreignPropery->getColumnName()
					) {
						$foreignPropery->setColumnName(
							$class->getTableName().'_'
							.$property->getConvertedName().'_id'
						);
					}
			
					$out .= "\$schema->\n"
						. "\taddTable(\n"
						. "\t\t".DBTable::class."::create('{$tableName}')->\n"
						. "\t\t\t{$property->toColumn()}->\n"
						. "\t\t\t{$foreignPropery->toColumn()}->\n"
						. "\t\t\taddUniques('{$property->getRelationColumnName()}', '{$foreignPropery->getColumnName()}')\n"
						. "\t\t);\n";
				} else {
					$sourceTable = $class->getTableName();
					$sourceColumn = $property->getRelationColumnName();
					
					$targetTable = $foreignClass->getTableName();
					$targetColumn = $foreignClass->getIdentifier()->getColumnName();
					
					$out .= "// {$sourceTable}.{$sourceColumn} -> {$targetTable}.{$targetColumn}\n"
						."\$schema->\n"
						."\tgetTableByName('{$sourceTable}')->\n"
						."\t\tgetColumnByName('{$sourceColumn}')->\n"
						."\t\t\tsetReference(\n"
						."\t\t\t\t\$schema->\n"
						."\t\t\t\t\tgetTableByName('{$targetTable}')->\n"
						."\t\t\t\t\tgetColumnByName('{$targetColumn}'),\n"
						."\t\t\t\t".ForeignChangeAction::class."::restrict(),\n"
						."\t\t\t\t".ForeignChangeAction::class."::cascade()\n"
						."\t\t);\n";
					}
				}
			}
			
			return $out;
		}
		
		public static function getHead()
		{
			$out = parent::getHead();
			
			$out .= "\$schema = new ".DBSchema::class."();\n\n";
			
			return $out;
		}
	}
?>