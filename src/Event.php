<?php
namespace AlexeyYashin\EasyEvents;

class Event
{
    protected $name = '';
    protected $parent = null;

    protected $result = null;

    protected $parameters = [];

    protected $reason = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function param(string $name)
    {
        return $this->parameters[$name] ?? null;
    }

    public function setParameters($parameters)
    {
        if (duck_check($parameters)->hasMethod('toArray')) {
            $parameters = call_user_func($parameters, 'toArray') ?: [];
        }

        if ( ! is_array($parameters)) {
            throw new \InvalidArgumentException('Argument 1 of ' . __METHOD__ . ' must be an array');
        }

        $this->parameters = $parameters;

        return $this;
    }

    public function addParameter(string $name, $value)
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param $result
     *
     * @return Event
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return null
     */
    public function getResult()
    {
        return $this->result;
    }

    public function isPreventable()
    {
        return false;
    }

    public function preventDefault(string $reason = null)
    {
        return $this;
    }

    public function isDefaultPrevented()
    {
        return false;
    }

    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
