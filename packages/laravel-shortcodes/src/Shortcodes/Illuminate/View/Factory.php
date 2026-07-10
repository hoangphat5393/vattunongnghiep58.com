<?php

namespace Gornymedia\Shortcodes\Illuminate\View;

use Gornymedia\Shortcodes\Shortcode;
use Illuminate\Events\Dispatcher;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory as IlluminateViewFactory;
use Illuminate\View\ViewFinderInterface;

class Factory extends IlluminateViewFactory
{
    /**
     * @var Shortcode
     */
    public $shortcode;

    /**
     * Factory constructor.
     */
    public function __construct(EngineResolver $engines, ViewFinderInterface $finder, Dispatcher $events, Shortcode $shortcode)
    {
        parent::__construct($engines, $finder, $events);

        $this->shortcode = $shortcode;
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array  $data
     * @param  array  $mergeData
     * @return View|string
     */
    public function make($view, $data = [], $mergeData = [])
    {
        if (isset($this->aliases[$view])) {
            $view = $this->aliases[$view];
        }

        $view = $this->normalizeName($view);
        $path = $this->finder->find($view);
        $data = array_merge($mergeData, $this->parseData($data));

        $this->callCreator($view = new View($this, $this->getEngineFromPath($path), $view, $path, $data, $this->shortcode));

        return $view;
    }
}
