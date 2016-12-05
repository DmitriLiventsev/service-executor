<?php

namespace ServiceExecutor;

/**
 * @author Dmitri Liventsev <dmitri@credy.eu>
 */
interface ServiceBeforeEventInterface
{
    /**
     * @param array $params
     */
    public function execute(array $params);
}