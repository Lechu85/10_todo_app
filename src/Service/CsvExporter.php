<?php

namespace App\Service;

class CsvExporter
{
	public function createResponseFromQueryBuilder(QueryBuilder $queryBuilder, FieldCollection $fields, string $filename): Response
	{
		$result = $queryBuilder->getQuery()->getArrayResult();
		// Convert DateTime objects into strings
		$data = [];
		foreach ($result as $index => $row) {
			foreach ($row as $columnKey => $columnValue) {
				$data[$index][$columnKey] = $columnValue instanceof \DateTimeInterface
					? $columnValue->format('Y-m-d H:i:s')
					: $columnValue;
			}
		}
	}
}