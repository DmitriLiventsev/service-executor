<?php
/**
 * @author Dmitri Liventsev <dmitri@credy.eu>
 */

namespace ServiceExecutor;

/**
 * @method validate()
 * @method getErrors()
 */
trait YiiValidatorTrait
{
    /**
     * @param array $params
     */
    public function validateParams(array $params)
    {
        /** @var mixed $param */
        Yii::configure($this, $params);

        return $this->validate();
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