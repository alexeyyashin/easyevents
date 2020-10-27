<?php
/**
 * @param string $name
 * @param bool   $preventable
 *
 * @return \AlexeyYashin\EasyEvents\Event|\AlexeyYashin\EasyEvents\PreventableEvent
 */
function event(string $name, bool $preventable = false): \AlexeyYashin\EasyEvents\Event
{
    if ($preventable) {
        $event = new \AlexeyYashin\EasyEvents\PreventableEvent($name);
    } else {
        $event = new \AlexeyYashin\EasyEvents\Event($name);
    }

    return $event;
}

/**
 * @param $event
 * @param $callback
 */
function listen($event, $callback)
{
    \AlexeyYashin\EasyEvents\Manager::addHandler($event, $callback);
}

/**
 * @param $event
 * @param $parameters
 *
 * @return \AlexeyYashin\EasyEvents\Event|\AlexeyYashin\EasyEvents\PreventableEvent
 */
function trigger($event, $parameters = [])
{
    return \AlexeyYashin\EasyEvents\Manager::triggerEvent($event, $parameters);
}
