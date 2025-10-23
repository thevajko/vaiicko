<?php

namespace Framework\Support;

/**
 * Lightweight view helper available inside templates.
 * Provides a clear API to set the layout without using globals.
 */
class View
{
    /** @var mixed Reference to layout slot in the renderer */
    private $layoutSlot;

    /**
     * @param mixed $layoutSlot Reference to a variable that stores selected layout name (or null)
     */
    public function __construct(& $layoutSlot)
    {
        // store reference so calls to layout() update the renderer state
        $this->layoutSlot =& $layoutSlot;
    }

    /**
     * Choose a layout for the current view.
     * Pass null to render without a layout.
     */
    public function setLayout(?string $name): void
    {
        $this->layoutSlot = $name;
    }
}

