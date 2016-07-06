<?php

namespace Bluetel\EzSelectionTwigBundle\Tests\Twig\Extension;

use Bluetel\EzSelectionTwigBundle\Twig\Extension\EzSelectionValue;
use eZ\Publish\Core\FieldType\Selection\Value as EzSelectionFieldValue;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\Repository\Values\ContentType\ContentType;

use Twig_Test_IntegrationTestCase;

/**
 * @covers \Bluetel\EzSelectionTwigBundle\Twig\Extension\EzSelectionValue
 */
class EzSelectionValueTest extends Twig_Test_IntegrationTestCase
{
    /**
     * @return array
     */
    protected function getExtensions()
    {
        $ezSelectionValue = new EzSelectionValue();
        $ezSelectionValue->setEzSelectionHelper(
            $this->getMockEzSelectionHelper()
        );
        return array(
            $ezSelectionValue
        );
    }

    /**
     * Get Mock eZSelectionHelper. We have to return a mock value for the
     * getContentType function
     *
     * @return EzSelection
     */
    protected function getMockEzSelectionHelper()
    {
        $service = $this->getMock(
            "Bluetel\\EzSelectionTwigBundle\\Core\\FieldHelper\\EzSelection",
            array('getContentType'),
            array(
                $this->getMock('eZ\\Publish\\API\\Repository\\Repository'),
                array('ezselection')
            )
        );

        $fieldIdentifier = 'test';

        $mockContentType = $this->getMockContentType(
            'test',
            array(
                $this->getFieldDefinition(
                    'test',
                    'ezselection',
                    array(
                        1 => 'Test_a',
                        2 => 'Test_b',
                        3 => 'Test_c',
                        4 => 'Test_d'
                    )
                )
            )
        );
        $service->method('getContentType')
            ->will(
                $this->returnValue(
                    $mockContentType
                )
            );
        return $service;
    }

    /**
     * Get a Mock content object
     *
     * @param  Field[] $fields array of the fields for the object.
     *
     * @return Content a content object with the fields inside.
     */
    public function getMockContentObject($fields)
    {
        return new Content(
            array(
                'internalFields' => $fields,
                'versionInfo' => new VersionInfo(
                    array(
                        'contentInfo' => new ContentInfo(
                            array('mainLanguageCode' => 'eng-GB')
                        )
                    )
                )
            )
        );
    }

    /**
     * Get a eZSelectionField value.
     *
     * @param  string $identifier      the identifier of the field.
     * @param  int[]  $selectedOptions array of the selected option ids for this field
     *
     * @return Field
     */
    public function getField($identifier, $selectedOptions)
    {
        return new Field(
            array(
                'value'=> new EzSelectionFieldValue($selectedOptions),
                'fieldDefIdentifier' => $identifier,
                'languageCode' => 'eng-GB'
            )
        );
    }

    /**
     * Get a mock ContentType with field definition in it.
     *
     * @param  string            $identifier       the identifier of the ContentType
     * @param  FieldDefinition[] $fieldDefinitions the field definitions of the ContentType.
     *
     * @return ContentType
     */
    public function getMockContentType($identifier, $fieldDefinitions)
    {
        return new ContentType(
            array(
                'identifier' => $identifier,
                'fieldDefinitions' => $fieldDefinitions
            )
        );
    }

    /**
     * Get a field definition object.
     *
     * @param  string      $identifier          identifier of the field definition.
     * @param  string      $fieldTypeIdentifier field type identifier of the field definition.
     * @param  array|null  $fieldOptions        the additional options for this field.
     *
     * @return FieldDefinition
     */
    public function getFieldDefinition($identifier, $fieldTypeIdentifier, $fieldOptions = null)
    {
        $data = array(
                    'identifier' => $identifier,
                    'fieldTypeIdentifier' => $fieldTypeIdentifier
                );
        if ($fieldOptions != null) {
            $data['fieldSettings'] = array(
                                        'options' => $fieldOptions
                                    );
        }
        return new FieldDefinition(
            $data
        );
    }

    /**
     * @return string
     */
    protected function getFixturesDir()
    {
        return __DIR__.'/_fixtures/ezselection_value';
    }
}
