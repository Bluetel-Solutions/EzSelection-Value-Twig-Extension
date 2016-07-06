<?php

namespace EzSystems\EzPriceBundle\Tests\Core\MultiPrice;

use Bluetel\EzSelectionTwigBundle\Core\FieldHelper\EzSelection;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\Selection\Value as EzSelectionValue;
use eZ\Publish\Core\Persistence\Legacy\Tests\TestCase;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use eZ\Publish\Core\Repository\Values\ContentType\ContentType;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;

/**
 * @covers \Bluetel\EzSelectionTwigBundle\Core\FieldHelper\EzSelection
 */
class EzSelectionTest extends TestCase
{
    /**
     * @covers EzSelection::getOptionNamesFromFieldDefintion
     * Test that we can handle a field value without any selection options.
     */
    public function testGetOptionNamesFromFieldDefintionEmptyOptions()
    {
        $service = new EzSelection(
                        $this->getMock('eZ\\Publish\\API\\Repository\\Repository'),
                        ['ezselection']
                    );

        $field = $this->getField('test', []);

        $fieldDefinition = $this->getFieldDefinition(
                                'test',
                                'ezselection',
                                [
                                    1 => 'Test',
                                ]
                            );

        $this->assertEquals(
            [],
            $service->getOptionNamesFromFieldDefintion(
                $field,
                $fieldDefinition
            )
        );
    }

    /**
     * @covers EzSelection::getOptionNamesFromFieldDefintion
     * Test that we can handle a field value with a single selected option.
     */
    public function testGetOptionNamesFromFieldDefintionSingleOption()
    {
        $service = new EzSelection(
                        $this->getMock('eZ\\Publish\\API\\Repository\\Repository'),
                        ['ezselection']
                    );

        $field = $this->getField('test', [1]);

        $fieldDefinition = $this->getFieldDefinition(
                                'test',
                                'ezselection',
                                [
                                    1 => 'Test_1',
                                    2 => 'Test_2',
                                    3 => 'Test_3',
                                ]
                            );

        $this->assertEquals(
            [
                1 => 'Test_1',
            ],
            $service->getOptionNamesFromFieldDefintion(
                $field,
                $fieldDefinition
            )
        );
    }

    /**
     * @covers EzSelection::getOptionNamesFromFieldDefintion
     * Test that we can handle a field value with a single selected option.
     */
    public function testGetOptionNamesFromFieldDefintionMultipleOptions()
    {
        $service = new EzSelection(
                        $this->getMock('eZ\\Publish\\API\\Repository\\Repository'),
                        ['ezselection']
                    );

        $field = $this->getField('test', [1, 3]);

        $fieldDefinition = $this->getFieldDefinition(
                                'test',
                                'ezselection',
                                [
                                    1 => 'Test_1',
                                    2 => 'Test_2',
                                    3 => 'Test_3',
                                ]
                            );

        $this->assertEquals(
            [
                1 => 'Test_1',
                3 => 'Test_3',
            ],
            $service->getOptionNamesFromFieldDefintion(
                $field,
                $fieldDefinition
            )
        );
    }

    /**
     * @covers EzSelection::getOptionNamesForField
     * Test that if we attempt to get the values from a field that we cannot use it will throw an exception.
     */
    public function testGetOptionNamesForFieldInvalidFieldTypeException()
    {
        $service = $this->getMock(
                        'Bluetel\\EzSelectionTwigBundle\\Core\\FieldHelper\\EzSelection',
                        ['getContentType'],
                        [
                            $this->getMock('eZ\\Publish\\API\\Repository\\Repository'),
                            ['ezselection'],
                        ]
                    );

        $fieldIdentifier = 'test';

        $mockContentType = $this->getMockContentType(
            'test',
            [
                $this->getFieldDefinition(
                    $fieldIdentifier,
                    'ezstring'
                ),
            ]
        );

        $service->method('getContentType')
            ->will(
                $this->returnValue(
                    $mockContentType
                )
            );

        $content = $this->getMockContentObject(
                        [
                            $this->getField(
                                $fieldIdentifier,
                                [1, 3]
                            ),
                        ]
                    );

        $this->setExpectedException('Bluetel\EzSelectionTwigBundle\API\FieldHelper\Exceptions\InvalidFieldTypeException');

        $service->getOptionNamesForField($content, $fieldIdentifier);
    }

    /**
     * @covers EzSelection::getOptionNamesForField
     * Test that we can get the values from a valid object and content type.
     */
    public function testGetOptionNamesForField()
    {
        $service = $this->getMock(
                        'Bluetel\\EzSelectionTwigBundle\\Core\\FieldHelper\\EzSelection',
                        ['getContentType'],
                        [
                            $this->getMock('eZ\\Publish\\API\\Repository\\Repository'),
                            ['ezselection'],
                        ]
                    );

        $fieldIdentifier = 'test';

        $mockContentType = $this->getMockContentType(
            'test',
            [
                $this->getFieldDefinition(
                    $fieldIdentifier,
                    'ezselection',
                    [
                        1 => 'Text_x',
                        2 => 'Text_y',
                        3 => 'Text_z',
                        4 => 'Text_a',
                    ]
                ),
            ]
        );

        $service->method('getContentType')
            ->will(
                $this->returnValue(
                    $mockContentType
                )
            );

        $content = $this->getMockContentObject(
                        [
                            $this->getField(
                                $fieldIdentifier,
                                [1, 3]
                            ),
                        ]
                    );

        $this->assertEquals(
            [
                1 => 'Text_x',
                3 => 'Text_z',
            ],
            $service->getOptionNamesForField($content, $fieldIdentifier)
        );
    }

    /**
     * @covers EzSelection::getOptionNamesForField
     * Test that we can handle a field value with a single selected option.
     */
    public function testGetOptionNamesForFieldFieldIdentifierNotFoundException()
    {
        $service = $this->getMock(
                        'Bluetel\\EzSelectionTwigBundle\\Core\\FieldHelper\\EzSelection',
                        ['getContentType'],
                        [
                            $this->getMock('eZ\\Publish\\API\\Repository\\Repository'),
                            ['ezselection'],
                        ]
                    );

        $fieldIdentifier = 'test';

        $mockContentType = $this->getMockContentType(
            'test',
            [
                $this->getFieldDefinition(
                    'test_fail',
                    'ezselection',
                    [
                        1 => 'Text_x',
                        2 => 'Text_y',
                        3 => 'Text_z',
                        4 => 'Text_a',
                    ]
                ),
            ]
        );

        $service->method('getContentType')
            ->will(
                $this->returnValue(
                    $mockContentType
                )
            );

        $content = $this->getMockContentObject(
                        [
                            $this->getField(
                                $fieldIdentifier,
                                [1, 3]
                            ),
                        ]
                    );

        $this->setExpectedException('Bluetel\EzSelectionTwigBundle\API\FieldHelper\Exceptions\FieldIdentifierNotFoundException');
        $service->getOptionNamesForField($content, $fieldIdentifier);
    }

    /**
     * Get a eZSelectionField value.
     *
     * @param string $identifier      the identifier of the field.
     * @param int[]  $selectedOptions array of the selected option ids for this field
     *
     * @return Field
     */
    public function getField($identifier, $selectedOptions)
    {
        return new Field(
            [
                'value'              => new EzSelectionValue($selectedOptions),
                'fieldDefIdentifier' => $identifier,
                'languageCode'       => 'eng-GB',
            ]
        );
    }

    /**
     * Get a field definition object.
     *
     * @param string     $identifier          identifier of the field definition.
     * @param string     $fieldTypeIdentifier field type identifier of the field definition.
     * @param array|null $fieldOptions        the additional options for this field.
     *
     * @return FieldDefinition
     */
    public function getFieldDefinition($identifier, $fieldTypeIdentifier, $fieldOptions = null)
    {
        $data = [
                    'identifier'          => $identifier,
                    'fieldTypeIdentifier' => $fieldTypeIdentifier,
                ];
        if ($fieldOptions != null) {
            $data['fieldSettings'] = [
                                        'options' => $fieldOptions,
                                    ];
        }

        return new FieldDefinition(
            $data
        );
    }

    /**
     * Get a mock ContentType with field definition in it.
     *
     * @param string            $identifier       the identifier of the ContentType
     * @param FieldDefinition[] $fieldDefinitions the field definitions of the ContentType.
     *
     * @return ContentType
     */
    public function getMockContentType($identifier, $fieldDefinitions)
    {
        return new ContentType(
            [
                'identifier'       => $identifier,
                'fieldDefinitions' => $fieldDefinitions,
            ]
        );
    }

    /**
     * Get a Mock content object.
     *
     * @param Field[] $fields array of the fields for the object.
     *
     * @return Content a content object with the fields inside.
     */
    public function getMockContentObject($fields)
    {
        return new Content(
            [
                'internalFields' => $fields,
                'versionInfo'    => new VersionInfo(
                    [
                        'contentInfo' => new ContentInfo(
                            ['mainLanguageCode' => 'eng-GB']
                        ),
                    ]
                ),
            ]
        );
    }
}
