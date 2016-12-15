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

        $this->executeBeforeEvent();
        $result = $this->service->execute($this->params);
        $this->executeAfterEvent();

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

    private function executeBeforeEvent()
    {
        if ($this->beforeEvent !== null) {
            $this->beforeEvent->execute($this->params);
        }
    }

    private function executeAfterEvent()
    {
        if ($this->afterEvent !== null) {
            $this->afterEvent->execute($this->params);
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
