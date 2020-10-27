<?php
namespace AlexeyYashin\EasyEvents;

class PreventableEvent extends Event
{
    protected $default_prevented = false;

    public function isPreventable()
    {
        return false;
    }

    public function preventDefault(string $reason = null)
    {
        $this->default_prevented = true;
        $this->reason = $reason;
        return $this;
    }

    public function isDefaultPrevented()
    {
        return $this->default_prevented;
    }
}
