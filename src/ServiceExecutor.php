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
     * BaseService constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator, ServiceInterface $service) {
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
        if ($this->validator->validate($params)) {
            $this->params = $params;

            $this->beforeEvent->execute($params);
            $result = $this->service->execute($params);
            $this->afterEvent->execute($params);

            return $result;
        } else {
            $errorMessage = $this->validator->getErrorMessage();
            throw new Exception(sprintf('Invalid parameters: %s', $errorMessage));
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
