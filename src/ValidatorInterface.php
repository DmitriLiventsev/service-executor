<?php

namespace ServiceExecutor;

/**
 * @author Dmitri Liventsev <dmitri@credy.eu>
 */
interface ValidatorInterface
{
    /**
     * @return bool
     */
    public function validateParams(array $params);

    /*
     * @return string
     */
    public function getErrorMessage();

    /**
     * @return array
     */
    public function getErrors();
}
