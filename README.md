# EzSelection-Twig-Bundle

Includes a twig extension that allows you to get the name values from and eZSelection field.

To enable add the following to your AppKernel.php file (called EzPublishKernel.php on some versions of eZ Publish).

    new Bluetel\EzSelectionTwigBundle\BluetelEzSelectionTwigBundle()

Clear your caches and you should be able to use the ezselection_value twig function within your twig templates.

#Example

    {% set optionNames = ezselection_value(content, 'selection_field_identifier') %}


In the example above the content variable will have to be a content object (eZ\Publish\API\Repository\Values\Content\Content). The second parameter will be the identifier of the field that you would like to retrieve.