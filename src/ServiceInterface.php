<?php

namespace ServiceExecutor;

/**
 * @author Dmitri Liventsev <dmitri@credy.eu>
 */
interface ServiceInterface
{
    /**
     * @param array $params
     * @return mixed
     */
    public function execute(array $params);
}