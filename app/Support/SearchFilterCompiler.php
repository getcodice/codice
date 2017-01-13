<?php

namespace Codice\Support;

class SearchFilterCompiler
{
    protected $errors;
    protected $query;
    protected $processors;

    /**
     * Initialize search filter compiler.
     *
     * @param string $query Query passed
     */
    public function __construct($query)
    {
        $this->registerProcessor('date', [$this, 'processDate']);
        $this->registerProcessor('status', [$this, 'processStatus']);

        $this->query = $this->normalizeQuery($query);
    }

    /**
     * Build SQL WHERE clause.
     *
     * @return string
     */
    public function buildClause() {
        $result = '';

        foreach ($this->tokenize() as $token)
        {
            if ($this->isOperator($token)) {
                $result .= strtoupper($token);
            } else {
                list($keyword, $value) = explode(':', $token);

                $result .= $this->callProcessor($keyword, $value);
            }

            // Always insert a space
            $result .= ' ';
        }

        // Check if there are any errors
        // If so, replace clause with an always-false condition so that search will return nothing
        if ($this->hasErrors()) {
            return '0 = 1';
        }

        return $result;
    }

    /**
     * Determine whether given string is filtering expression possible to be compiled.
     *
     * @return bool
     */
    public function isFilter() {
        $keywords = implode('|', $this->getKeywords());

        return (bool) preg_match("#^($keywords):[^\\s]+#", $this->query);
    }

    /**
     * Register new processor.
     *
     * @param  string $keyword Keyword the processor is triggered for
     * @param  callable $processor Callable processing data passed for given keyword.
     *                             string $value and string $operator are passed as
     *                             parameters.
     * @return void
     */
    public function registerProcessor($keyword, callable $processor)
    {
        $this->processors[$keyword] = $processor;
    }

    /**
     * Call the processor for given token.
     *
     * @param  string $keyword Token's keyword
     * @param  string $value Token's value
     * @return string
     */
    protected function callProcessor($keyword, $value)
    {
        if (substr($value, 0, 1) == '!') {
            $operator = '!=';
            $value = substr($value, 1);
        } else {
            $operator = '=';
        }

        return $this->processors[$keyword]($value, $operator);
    }

    /**
     * Get all keywords registered by the compiler.
     *
     * @return string[]
     */
    protected function getKeywords()
    {
        return array_keys($this->processors);
    }

    /**
     * Determine whether given token is an supported operator.
     *
     * @param  string $token
     * @return bool
     */
    protected function isOperator($token)
    {
        return in_array($token, ['and', 'or']);
    }

    /**
     * Determine wtether compilation ended up with any errors.
     *
     * @return bool
     */
    protected function hasErrors()
    {
        return $this->errors;
    }

    /**
     * Attempt to normalize passed query for easier parsing.
     *
     * @param  string $query
     * @return string
     */
    protected function normalizeQuery($query)
    {
        $query = preg_replace('!\s+!', ' ', $query); // deduplicate whitespace
        $query = strtolower($query);

        return $query;
    }

    /**
     * Process the "date" filter.
     *
     * Value must be valid accordingly to the PHP's date parsing rules.
     *
     * @param  string $value
     * @param  string $operator
     * @return string
     */
    protected function processDate($value, $operator)
    {
        $result = '';

        $date = date_parse($value);

        if ($date['error_count']) {
            $this->raiseError();
        }

        if ($date['year']) {
            $result .= " AND YEAR(created_at) $operator '{$date['year']}'";
        }
        if ($date['month']) {
            $result .= " AND MONTH(created_at) $operator '{$date['month']}'";
        }
        if ($date['day']) {
            $result .= " AND DAY(created_at) $operator '{$date['day']}'";
        }

        return $this->stripOperatorFromStart($result);
    }

    /**
     * Process the "status" filter.
     *
     * @param  string $value
     * @param  string $operator
     * @return string
     */
    protected function processStatus($value, $operator)
    {
        if ($value == 'done') {
            $status = 1;
        } elseif ($value == 'undone') {
            $status = 0;
        } else {
            $status = null;
            $this->raiseError();
        }

        return "status $operator $status";
    }

    /**
     * Set the compiler's state to errored.
     *
     * @return void
     */
    protected function raiseError()
    {
        $this->errors = true;
    }

    /**
     * Strip SQL operator from the beginning of the string.
     *
     * @param  string $string
     * @return string
     */
    protected function stripOperatorFromStart($string)
    {
        $string = trim(strtolower($string));
        $words = explode(' ', $string);

        if (isset($words[0]) && $this->isOperator($words[0])) {
            $string = substr($string, strlen($words[0]) + 1);
        }

        return $string;
    }

    /**
     * Split given query string into tokens.
     *
     * @return string[]
     */
    protected function tokenize()
    {
        return explode(' ', $this->query);
    }
}
