<?php

namespace ServiceExecutor;

use Exception;

/**
 * @author Dmitri Liventsev <dmitri.liventsev@gmail.com>
 */
class ServiceExecutor
{
    /** @var  ValidatorInterface */
    private $validator;

    /** @var  array */
    private $params;

    /** @var  ServiceInterface */
    private $service;

    /** @var  ServiceBeforeEventInterface */
    private $beforeEvent;

    /** @var  ServiceAfterEventInterface */
    private $afterEvent;

    /**
     * @param ServiceInterface $service
     * @param ValidatorInterface $validator
     */
    public function __construct(ServiceInterface $service, ValidatorInterface $validator) {
        $this->validator = $validator;
        $this->service = $service;
    }

    /**
     * @param array $params
     * @throws Exception
     *
     * @return mixed
     */
    public function execute(array $params)
    {
        $this->setParams($params);

        $this->executeBeforeEvent($params);
        $result = $this->service->execute($params);
        $this->executeAfterEvent($params);

        return $result;
    }

    /**
     * @param array $params
     * @throws Exception
     */
    public function setParams(array $params)
    {
        $this->params = $this->validate($params);
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    private function validate(array $params)
    {
        if (!$this->validator->validateParams($params)) {
            $errorMessage = $this->validator->getErrorMessage();

            throw new Exception(sprintf('Invalid parameters: %s', $errorMessage));
        }

        return $params;
    }

    /**
     * @param array $params
     */
    private function executeBeforeEvent(array $params)
    {
        if ($this->beforeEvent !== null) {
            $this->beforeEvent->execute($params);
        }
    }

    /**
     * @param array $params
     */
    private function executeAfterEvent(array $params)
    {
        if ($this->afterEvent !== null) {
            $this->afterEvent->execute($params);
        }
    }

    /**
     * @return array|null
     */
    public function getAllErrors()
    {
        return $this->validator->getErrors();
    }

    /**
     * @return ServiceBeforeEventInterface
     */
    public function getBeforeEvent()
    {
        return $this->beforeEvent;
    }

    /**
     * @param ServiceBeforeEventInterface $beforeEvent
     */
    public function setBeforeEvent($beforeEvent)
    {
        $this->beforeEvent = $beforeEvent;
    }

    /**
     * @return ServiceAfterEventInterface
     */
    public function getAfterEvent()
    {
        return $this->afterEvent;
    }

    /**
     * @param ServiceAfterEventInterface $afterEvent
     */
    public function setAfterEvent($afterEvent)
    {
        $this->afterEvent = $afterEvent;
    }
}
