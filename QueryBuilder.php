<?php


namespace root;


use InvalidArgumentException;

class QueryBuilder
{
    private array $queryArray;

    /**
     * @param string $fileTerm
     * @param string $operator
     * @param array  $values
     *
     * @return $this
     */
    public function addItem(string $fileTerm, string $operator, array $values): QueryBuilder
    {
        QueryFileTerms::validate($fileTerm, $operator);
        $this->queryArray[] = $this->buildMultiCondition($values, $fileTerm);

        return $this;
    }

    /**
     * @param array  $values
     * @param string $attributeName
     *
     * @return string
     */
    private function buildMultiCondition(array $values, string $attributeName): string
    {
        $preparedTargets = [];
        foreach ($values as $target) {
            $preparedTargets[] = "{$attributeName}='{$target}'";
        }

        return '(' . implode(' or ', $preparedTargets) . ')';
    }

    /**
     * @param string $combineOperator
     *
     * @return string
     */
    public function getQuery(string $combineOperator = ''): string
    {
        if (empty($this->queryArray)) {
            return '';
        }

        if (count($this->queryArray) === 1) {
            return $this->queryArray[0];
        }

        if (empty($combineOperator)) {
            throw new InvalidArgumentException("Query array contains more than 1 query term and combine operator [OR/AND] must be provided.");
        }
        QueryOperators::validateCombineOperator($combineOperator);

        return implode(" {$combineOperator} ", $this->queryArray);
    }
}