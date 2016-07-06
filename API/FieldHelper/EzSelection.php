<?php

namespace Bluetel\EzSelectionTwigBundle\API\FieldHelper;

use eZ\Publish\API\Repository\Values\Content\Content;

interface EzSelection
{
    /**
     * Get the option names for a field value.
     *
     * @param Content $content the content object to get the field from.
     * @param string  $field   the identifier of the field.
     *
     * @return string[] Array of the selected option names.
     */
    public function getOptionNamesForField(Content $content, $fieldIdentifier);
}
