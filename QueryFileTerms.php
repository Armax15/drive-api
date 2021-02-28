<?php


namespace root;


use InvalidArgumentException;

abstract class QueryFileTerms
{
    /***
     *  name	                    contains, =, !=	        Name of the file. Surround with single quotes '. Escape single quotes in queries with \', e.g., 'Valentine\'s Day'.
        fullText	                contains    	        Full text of the file including name, description, content, and indexable text. Whether the 'title', 'description', or 'indexableText' properties, or the content of the file matches. Surround with single quotes '. Escape single quotes in queries with \', e.g., 'Valentine\'s Day'.
        mimeType	                contains, =, !=	        MIME type of the file. Surround with single quotes '. Escape single quotes in queries with \', e.g., 'Valentine\'s Day'.
        modifiedTime	            <=, <, =, !=, >, >=	    Date of the last modification of the file. RFC 3339 format, default timezone is UTC, e.g., 2012-06-04T12:00:00-08:00. Fields of type date are not currently comparable to each other, only to constant dates.
        viewedByMeTime	            <=, <, =, !=, >, >=	    Date that the user last viewed a file. RFC 3339 format, default timezone is UTC, e.g., 2012-06-04T12:00:00-08:00. Fields of type date are not currently comparable to each other, only to constant dates.
        trashed	                    =, !=	                Whether the file is in the trash or not. Can be either true or false.
        starred	                    =, !=	                Whether the file is starred or not. Can be either true or false.
        parents	                    in	                    Whether the parents collection contains the specified ID.
        owners4	                    in	                    Users who own the file.
        writers4	                in	                    Users or groups who have permission to modify the file. See Permissions resource reference.
        readers4	                in	                    Users or groups who have permission to read the file. See Permissions resource reference.
        sharedWithMe	            =, !=	                Files that are in the user's "Shared with me" collection. All file users are in the file's access control list (ACL). Can be either true or false.
        properties	                has	                    Public custom file properties.
        appProperties	            has	                    Private custom file properties.
        visibility	                =, '!='	                The visibility level of the file. Valid values are anyoneCanFind, anyoneWithLink, domainCanFind, domainWithLink, and limited. Surround with single quotes '. Escape single quotes in queries with \', e.g., 'Valentine\'s Day'.
        shortcutDetails.targetId	=, !=
     *
     */

    public const TERM_NAME      = 'name';
    public const TERM_MIME_TYPE = 'mimeType';

    private const TERMS_WITH_AVAILABLE_OPERATORS = [
        self::TERM_NAME => [
            QueryOperators::OPERATOR_CONTAINS  => true,
            QueryOperators::OPERATOR_EQUAL     => true,
            QueryOperators::OPERATOR_NOT_EQUAL => true,
        ],
        self::TERM_MIME_TYPE => [
            QueryOperators::OPERATOR_CONTAINS  => true,
            QueryOperators::OPERATOR_EQUAL     => true,
            QueryOperators::OPERATOR_NOT_EQUAL => true,
        ],
    ];

    /**
     * @param string $term
     * @param string $operator
     */
    public static function validate(string $term, string $operator): void
    {
        if (empty(self::TERMS_WITH_AVAILABLE_OPERATORS[$term][$operator])) {
            $possibleValues = implode(',', array_keys(self::TERMS_WITH_AVAILABLE_OPERATORS[$term]));

            throw new InvalidArgumentException("Query file term [{$term}] doesn't accept operator [{$operator}]. Possible values [{$possibleValues}].");
        }
    }
}