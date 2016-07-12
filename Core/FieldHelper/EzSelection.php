<?php

namespace Bluetel\EzSelectionTwigBundle\Core\FieldHelper;

use Bluetel\EzSelectionTwigBundle\API\FieldHelper\Exceptions\FieldIdentifierNotFoundException;
use Bluetel\EzSelectionTwigBundle\API\FieldHelper\Exceptions\InvalidFieldTypeException;
use Bluetel\EzSelectionTwigBundle\API\FieldHelper\EzSelection as EzSelectionHelperInterface;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\Repository\Values\ContentType\ContentType;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;

class EzSelection implements EzSelectionHelperInterface
{
    /**
     * $repository eZPublish's API Repository.
     */
    protected $repository;

    /**
     * $acceptedFieldTypeStrings Field Types that can be accepted with this class.
     *
     * @var string[]
     */
    protected $acceptedFieldTypeStrings;

    /**
     * __construct.
     *
     * @param          $repository               eZPublish's API Repository.
     * @param string[] $acceptedFieldTypeStrings Field types that will be processed.
     */
    public function __construct($repository, $acceptedFieldTypeStrings = array())
    {
        $this->repository = $repository;
        $this->acceptedFieldTypeStrings = $acceptedFieldTypeStrings;
    }

    /**
     * {@inherit}.
     */
    public function getOptionNamesForField(Content $content, $fieldIdentifier)
    {
        $contentType = $this->getContentType($content);

        $fieldDefinition = $contentType->getFieldDefinition($fieldIdentifier);

        if (!$fieldDefinition instanceof FieldDefinition) {
            throw new FieldIdentifierNotFoundException('Field', $fieldIdentifier);
        }

        if (!in_array($fieldDefinition->fieldTypeIdentifier, $this->acceptedFieldTypeStrings)) {
            throw new InvalidFieldTypeException('$fieldIdentifier', "Field with identifier '{$fieldIdentifier}' is of invalid field type {$fieldDefinition->fieldTypeIdentifier}. This type of field cannot be processed.");
        }

        return $this->getOptionNamesFromFieldDefintion(
                    $content->getField($fieldIdentifier),
                    $fieldDefinition
                );
    }

    /**
     * Get the selection option names field definition.
     *
     * @param Field           $contentField   The content field to get the options from.
     * @param FieldDefinition $fieldDefintion The field definition to get the option names from.
     *
     * @return string[] array of the selection option names
     */
    public function getOptionNamesFromFieldDefintion(Field $contentField, FieldDefinition $fieldDefintion)
    {
        $fieldSettings = $fieldDefintion->getFieldSettings();

        $optionNames = array();

        foreach ($contentField->value->selection as $selectionOptionId) {
            $optionNames[$selectionOptionId] = $fieldSettings['options'][$selectionOptionId];
        }

        return $optionNames;
    }

    /**
     * Get the content type for the object.
     *
     * @param Content $content The content object to load the content type for.
     *
     * @throws eZ\Publish\Core\Persistence\Legacy\Exception\TypeNotFound if the content type is not found.
     *
     * @return ContentType the content type for the content object.
     */
    public function getContentType($content)
    {
        return $this->repository
                    ->getContentTypeService()
                    ->loadContentType(
                        $content->getVersionInfo()
                                ->getContentInfo()
                                ->contentTypeId
                    );
    }
}
