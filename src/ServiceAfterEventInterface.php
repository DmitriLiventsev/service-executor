<?php

namespace ServiceExecutor;

/**
 * @author Dmitri Liventsev <dmitri@credy.eu>
 */
interface ServiceAfterEventInterface
{
    /**
     * @param array $params
     */
    public function execute(array $params);
}