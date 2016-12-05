<?php
/**
 * @author Dmitri Liventsev <dmitri@credy.eu>
 */

namespace ServiceExecutor;

trait YiiValidatorTrait
{
    /**
     * @param array $params
     */
    public function validate(array $params)
    {
        /** @var mixed $param */
        foreach ($params as $key => $param) {
            $this->$key = $param;
        }

        parent:validate($params);
    }

    /**
     * @return null|string
     */
    public function getErrorMessage()
    {
        $errorMessage = null;
        $errors = $this->getErrors();

        if (!empty($errors)) {
            $paramErrors = [];
            /**
             * @var string $paramKey
             * @var  array $errorsStack
             */
            foreach ($errors as $paramKey => $errorsStack) {
                $paramErrors[] = sprintf('"%s" => [ %s ]', $paramKey, implode(" . ", $errorsStack));
            }

            $errorMessage = implode(" | " . $paramErrors);
        }

        return $errorMessage;
    }
}