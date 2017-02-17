<?php

namespace genonbeta\util;

use genonbeta\controller\Controller;

class ValidateForm
{
    const DISABLED = -1;

    const ERROR_TOO_LONG = "errorTooLong";
    const ERROR_TOO_SHORT = "errorTooShort";
    const ERROR_MATCH_CASE = "errorMatchCase";
    const ERROR_EMPTY = "errorEmpty";
    const ERROR_FIELD_CHECK = "errorFieldCheck";

    const FRIENDLY_NAME = "friendlyName";
    const MIN_LENGHT = "minLenght";
    const MAX_LENGHT = "maxLenght";
    const MATCH_CASE = "matchCase";
    const CONTROLLER = "controller";
    const CALLABLE = "callable";
    const NULLABLE = "nullable";
    const FIELD_CHECK = "fieldCheck";
    const ERROR_CODE = "errorCode";
    const RESULT_VALUE = "resultValue";

    // NULLABLE::value
    const NULL = true;
    const NOT_NULL = false;

    private $fields = [];
    private $errorMsgs = [];
    private $selected = null;
    private $gatherFunction;

    function __construct($gatherFunction = null)
    {
        $this->setGatherFunction($gatherFunction);

        $this->errorMsgs = [
            self::ERROR_TOO_LONG => "%s is too long",
            self::ERROR_TOO_SHORT => "%s is too short",
            self::ERROR_MATCH_CASE => "%s is not suitable to use",
            self::ERROR_EMPTY => "%s can't be empty",
            self::ERROR_FIELD_CHECK => "%s should match to the other one"
        ];
    }

    public function define($variable, $friendlyName)
    {
        $this->fields[$variable] = [
            self::FRIENDLY_NAME => $friendlyName,
            self::MIN_LENGHT => self::DISABLED,
            self::MAX_LENGHT => self::DISABLED,
            self::MATCH_CASE => self::DISABLED,
            self::CONTROLLER => self::DISABLED,
            self::CALLABLE => self::DISABLED,
            self::NULLABLE => self::DISABLED,
            self::FIELD_CHECK => self::DISABLED
        ];

        $this->select($variable);

        return $this;
    }

    public function defineError($errorCode, $errorMsg)
    {
        $this->errorMsg[$errorCode] = $errorCode;
    }

    public function deploy()
    {
        $gatherFunction = $this->gatherFunction;

        foreach ($this->fields as $key => $index)
        {
            $value = $gatherFunction($key);
            $error = null;

            // remove previous error set
            unset($this->fields[$key][self::ERROR_CODE]);

            // remove previous result value
            unset($this->fields[$key][self::RESULT_VALUE]);

            if ($index[self::NULLABLE] == self::NOT_NULL && empty($value))
                 $error = self::ERROR_EMPTY;
            elseif ($index[self::MAX_LENGHT] != self::DISABLED && strlen($value) > $index[self::MAX_LENGHT])
                $error = self::ERROR_TOO_LONG;
            elseif ($index[self::MIN_LENGHT] != self::DISABLED && strlen($value) < $index[self::MIN_LENGHT])
                $error = self::ERROR_TOO_SHORT;
            elseif (
                        ($index[self::CALLABLE] != self::DISABLED && !$index[self::CALLABLE]($value)) ||
                        ($index[self::MATCH_CASE] != self::DISABLED && !preg_match($index[self::MATCH_CASE], $value)) ||
                        ($index[self::CONTROLLER] != self::DISABLED && !$index[self::CONTROLLER]->onRequest($value))
                    )
                $error = self::ERROR_MATCH_CASE;
            elseif ($index[self::FIELD_CHECK] != self::DISABLED && $value != $gatherFunction($index[self::FIELD_CHECK]))
                $error = self::ERROR_FIELD_CHECK;

            if ($error != null)
                $this->fields[$key][self::ERROR_CODE] = $error;
            else
                $this->fields[$key][self::RESULT_VALUE] = $value;
        }

        return $this;
    }

    public function getFriendlyName($variable)
    {
        return isset($this->fields[$variable][self::FRIENDLY_NAME]) ? $this->fields[$variable][self::FRIENDLY_NAME] : null;
    }

    public function getErrorMsg($errorCode)
    {
        return isset($this->errorMsgs[$errorCode]) ? $this->errorMsgs[$errorCode] : null;
    }

    public function getErrorMsgForField($variable)
    {
        return $this->hasFieldError($variable) ? $this->getErrorMsg($this->fields[$variable][self::ERROR_CODE]): null;
    }

    public function getFieldObserver($key, $index)
    {
        if (!$this->has($key))
            return false;

        return $this->fields[$key][$index];
    }

    public function getErrors()
    {
        $returnedIndex = [];

        foreach($this->fields as $key => $index)
            if (isset($index[self::ERROR_CODE]))
                $returnedIndex[$key] = $index;

        return $returnedIndex;
    }

    public function getGatherFunction()
    {
        return $this->gatherFunction;
    }

    public function getResultValue($key)
    {
        return $this->getFieldObserver($key, self::RESULT_VALUE);
    }

    public function has($variable)
    {
        return isset($this->fields[$variable]);
    }

    public function hasFieldError($variable)
    {
        return isset($this->fields[$variable][self::ERROR_CODE]);
    }

    public function select($variable)
    {
        if (!$this->has($variable))
            return false;

        $this->selected = $variable;
        return $this;
    }

    public function setGatherFunction($gatherFunction)
    {
        $this->gatherFunction = $gatherFunction;

        return $this;
    }

    public function setCallable($callable)
    {
        if (!is_callable($callable))
            return false;

        return $this->updateFieldObserver(self::CALLABLE, $callable);
    }

    public function setController(Controller $controller)
    {
        return $this->updateFieldObserver(self::CONTROLLER, $controller);
    }

    public function setFieldCheck($otherFieldId)
    {
        if (!$this->has($otherFieldId))
            return false;

        return $this->updateFieldObserver(self::FIELD_CHECK, $otherFieldId);
    }

    public function setMaxLenght($maxLenght)
    {
        if (!is_int($maxLenght))
            return false;

        return $this->updateFieldObserver(self::MAX_LENGHT, $maxLenght);
    }

    public function setMinLenght($minLenght)
    {
        if (!is_int($minLenght))
            return false;

        return $this->updateFieldObserver(self::MIN_LENGHT, $minLenght);
    }

    public function setNullable($isNullable)
    {
        return $this->updateFieldObserver(self::NULLABLE, $isNullable ? self::NULL : self::NOT_NULL);
    }

    private function updateFieldObserver($index, $value)
    {
        if ($this->selected == null)
            return false;

        $this->fields[$this->selected][$index] = $value;

        return $this;
    }
}
