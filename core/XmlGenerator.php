<?php

class XmlGenerator
{
    /**
     * Genera una cadena XML a partir de un array de datos.
     *
     * @param array $data El array asociativo con los datos a convertir en XML.
     * @param string $rootElementName El nombre del elemento raíz del XML.
     * @param string $encoding La codificación del XML (por defecto 'utf-8').
     * @return string La cadena XML generada.
     */
    public static function generateXmlFromArray(array $data, string $rootElementName): string
    {

        $dom = new DOMDocument();
        $dom->formatOutput = true; // Para un XML legible con indentación
        $rootElement = $dom->createElement($rootElementName);
        $dom->appendChild($rootElement);

        self::appendArrayToXml($dom, $rootElement, $data);


        return $dom->saveXML();
    }

    /**
     * Función recursiva para añadir elementos de un array a un nodo XML.
     *
     * @param DOMDocument $dom El objeto DOMDocument.
     * @param DOMElement $parentElement El elemento padre al que se añadirán los nuevos elementos.
     * @param array $data El array con los datos.
     */
    private static function appendArrayToXml(DOMDocument $doc, DOMElement $parentNode, array $array)
    {
        foreach ($array as $key => $value) {
            // Determine the element name based on the key type
            if (is_int($key)) {
                // If the key is an integer (like 0, 1, etc.), use a generic name
                $elementName = 'item';
            } else {
                // Otherwise, use the key as the element name
                $elementName = $key;
            }

            if (is_array($value)) {
                // Create a child node for the nested array and recursively append its contents
                $childNode = $doc->createElement($elementName);
                self::appendArrayToXml($doc, $childNode, $value);
                $parentNode->appendChild($childNode);
            } else {
                // Create a child node for a scalar value and append it
                $childNode = $doc->createElement($elementName, htmlspecialchars($value));
                $parentNode->appendChild($childNode);
            }
        }
    }
}