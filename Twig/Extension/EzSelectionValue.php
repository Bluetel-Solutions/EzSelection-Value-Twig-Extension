<?php

namespace Bluetel\EzSelectionTwigBundle\Twig\Extension;

use Bluetel\EzSelectionTwigBundle\API\FieldHelper\EzSelection as EzSelectionHelper;
use eZ\Publish\API\Repository\Values\Content\Content;
use Twig_Extension;
use Twig_SimpleFunction;

class EzSelectionValue extends Twig_Extension
{
    /**
     * $ezSelectionHelper Helper used to get the option names.
     *
     * @var EzSelectionHelper
     */
    protected $ezSelectionHelper;

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ezselection_value';
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction(
                'ezselection_value',
                array($this, 'getSelectionValues')
            ),
        );
    }

    /**
     * Get selection value names from a content for a particular field.
     *
     * @param Content $content         The content object to get the value from.
     * @param string  $fieldIdentifier The field identifier to get the value for.
     *
     * @return string[] array of option names as strings.
     */
    public function getSelectionValues(Content $content, $fieldIdentifier)
    {
        return $this->ezSelectionHelper->getOptionNamesForField($content, $fieldIdentifier);
    }

    /**
     * Set the ezselection helper. Called by container.
     *
     * @param EzSelectionHelper $ezSelectionHelper EzSelectionHelper go get the option values from.
     */
    public function setEzSelectionHelper(EzSelectionHelper $ezSelectionHelper)
    {
        $this->ezSelectionHelper = $ezSelectionHelper;

        return $this;
    }
}
