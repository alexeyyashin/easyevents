<?php
namespace AlexeyYashin\EasyEvents;

class Manager
{
    protected static $_instance;

    protected $listeners = [];

    protected function __construct()
    {

    }

    protected function __clone()
    {
        // TODO: Implement __clone() method.
    }

    protected static function getInstance(): Manager
    {
        if (static::$_instance === null) {
            static::$_instance = new static;
        }

        return static::$_instance;
    }

    protected static function extractArray(string $method, int $arg, $value, $null = false)
    {
        if (
            is_array($value)
            || (
                $null
                && $value === null
            )
        ) {
            return $value;
        }

        $check = duck_check($value);
        if ($check->hasMethod('toArray')) {
            return $value->toArray();
        }
        if ($check->implementing(\ArrayObject::class)) {
            return (array) $value;
        }

        throw new \InvalidArgumentException('Argument ' . $arg . ' of ' . $method . ' must be an array');
    }

    public static function addHandler(string $event, callable $callback)
    {
        static::getInstance()->listeners[$event][] = $callback;
    }

    /**
     * @param string|\AlexeyYashin\EasyEvents\Event $event
     * @param array                                 $params
     * @param bool                                  $preventable    - makes sence only if $event is string
     * @param null                                  $default_result - if not null overrides default event result
     *
     * @return \AlexeyYashin\EasyEvents\Event
     */
    public static function triggerEvent($event, $params = [], $preventable = false, $default_result = null): Event
    {
        if (is_string($event)) {
            if ($preventable = true) {
                $event = new PreventableEvent($event);
            } else {
                $event = new Event($event);
            }

            $event->setResult($default_result);
        } else {
            if ( ! duck_check($event)->implementing(Event::class)) {
                throw new \InvalidArgumentException('Argument ' . 1 . ' of ' . __METHOD__
                    . ' must be type of string or ' . Event::class);
            }
        }

        $params = self::extractArray(__METHOD__, 2, $params, true);

        /*
         * Because event can have default parameters
         */
        foreach ($params as $k => $v) {
            $event->addParameter($k, $v);
        }

        $preventable = $event->isPreventable();

        if ($default_result !== null) {
            $event->setResult($default_result);
        }

        foreach (static::getInstance()->listeners[$event->getName()] ?:[] as $item) {
            call_user_func_array($item, [$event]);

            if ($preventable && $event->isDefaultPrevented()) {
                break;
            }
        }

        return $event;
    }
}
