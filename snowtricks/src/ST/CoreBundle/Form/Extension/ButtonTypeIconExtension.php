<?php

namespace ST\CoreBundle\Form\Extension;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class ButtonTypeIconExtension extends AbstractIconExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
    	// extend all buttons
        return ButtonType::class;
    }
}