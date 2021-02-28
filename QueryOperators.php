<?php


namespace root;

use InvalidArgumentException;

/**
 * Represents query operators
 *
 * Class QueryOperators
 * @package root
 */
abstract class QueryOperators
{
    public const COMBINE_OPERATOR_AND = 'and';
    public const COMBINE_OPERATOR_OR  = 'or';

    private const AVAILABLE_COMBINE_OPERATORS = [
        self::COMBINE_OPERATOR_AND => true,
        self::COMBINE_OPERATOR_OR  => true,
    ];
    /**
     * contains    The content of one string is present in the other.
     * =    The content of a string or boolean is equal to the other.
     * !=	The content of a string or boolean is not equal to the other.
     * <	A value is less than another.
     * <=	A value is less than or equal to another.
     * >	A value is greater than another.
     * >=	A value is greater than or equal to another.
     * in	An element is contained within a collection.
     * and	Return items that match both queries.
     * or	Return items that match either query.
     * not	Negates a search query.
     * has	A collection contains an element matching the parameters.
     */
    public const OPERATOR_CONTAINS      = 'contains';
    public const OPERATOR_EQUAL         = '=';
    public const OPERATOR_NOT_EQUAL     = '!=';

    /**
     * @param string $combineOperator
     */
    public static function validateCombineOperator(string $combineOperator): void
    {
        if (empty(self::AVAILABLE_COMBINE_OPERATORS[$combineOperator])) {
            $possibleValues = implode('/', self::AVAILABLE_COMBINE_OPERATORS);

            throw new InvalidArgumentException("Invalid query combine operator [{$combineOperator}], possible values [{$possibleValues}].");
        }
    }
}