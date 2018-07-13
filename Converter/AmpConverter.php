<?php

namespace Elephantly\AmpConverterBundle\Converter;

use DOMDocument;
use DOMXPath;
use Elephantly\AmpConverterBundle\Converter\Media\AmpImgConverter;
use Elephantly\AmpConverterBundle\Converter\ConverterChain;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Elephantly\AmpConverterBundle\Cleaner\AmpTagCleaner;

/**
* primary @author purplebabar(lalung.alexandre@gmail.com)
*/
class AmpConverter
{
    protected $input;

    protected $output;

    protected $options;

    protected $converters;

    protected $cleaner;

    public function __construct($converters = array(), $options = array(), $cleaner = null)
    {
        $this->options = $options;
        $this->converters = $converters;
        $this->cleaner = $cleaner;
        if ($converters instanceof ConverterChain) {
            $this->converters = $converters->getConverters();
        }
    }

    public function convert($input)
    {
        if (!$input) {
            return '';
        }

        $document = $this->getDom($input);

        // convert Tags
        foreach ($this->converters as $selector => $converterClass) {
            $tags = $this->getMatchingTags($document, $selector);

            $converter = $this->getConverter($converterClass);

            foreach ($tags as $tag) {
                $this->convertTag($tag, $converter);
            }
        }

        // Clean Tags
        if($document && $this->cleaner) {
            $document = $this->cleaner->cleanIllegalTagAttributes($document);
            $document = $this->cleaner->cleanIllegalTags($document, $this->options);
        }


        // Workaround 2 Working for 53
        // https://stackoverflow.com/questions/5706086/php-domdocument-output-without-xml-version-1-0-encoding-utf-8
        // $output = $document->saveXML($document->documentElement->firstChild->firstChild); // still not working
        // $output = $document->saveXML($document->documentElement); // seems to be valid

        // Only workaround Working for real
        $output = '';
        $outputElement = $document->documentElement;

        if ($outputElement->firstChild->tagName === 'body') {
            $outputElement = $outputElement->firstChild;
        }

        foreach ($outputElement->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        if($output && $this->cleaner) {
            $output = $this->cleaner->cleanillegalAttributes($output);
        }

        return $output;

    }

    public function getAmpScripts($input)
    {
        $scripts = array();

        if (!$input) {
            return '';
        }

        $document = $this->getDom($input);

        foreach ($this->converters as $converterClass) {
            $converter = $this->getConverter($converterClass);
            $tags = $this->getMatchingTags($document, $converter->getAmpTagName());

            if (!in_array($converter->getScriptTag(), $scripts) && $tags->length && $converter->hasScriptTag()) {
                $scripts[] = $converter->getScriptTag();
            }
        }

        return implode('', $scripts);
    }

    private function getDom($input)
    {
        $document = new DOMDocument("1.0", "UTF-8");
        libxml_use_internal_errors(true);
        // not working in 53
        // see https://stackoverflow.com/questions/4879946/how-to-savehtml-of-domdocument-without-html-wrapper
        // $document->loadHTML($input, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // WORKAROUND 1 not working either
        // $fragment = $document->createDocumentFragment();
        // $fragment->appendXML($input);
        //
        // $document->appendChild($fragment);

        // UTF8 encoding issue see:
        // https://stackoverflow.com/questions/11309194/php-domdocument-failing-to-handle-utf-8-characters#11310258
        $cleanInput = mb_convert_encoding($input, 'HTML-ENTITIES', 'UTF-8');

        $document->loadHTML($cleanInput);
        $document->encoding = 'UTF-8';
        libxml_clear_errors();

        return $document;
    }

    private function getMatchingTags(DOMDocument $document, $selector)
    {
        $selectorConverter = new CssSelectorConverter();

        $xpathSelector = $selectorConverter->toXPath($selector);

        $xpath = new DOMXPath($document);

        $elements = $xpath->query($xpathSelector);
        return $elements;
    }

    private function getConverter($converterClass)
    {
        $identifier = $converterClass::getIdentifier();
        $tagOptions = isset($this->options[$identifier]) ? $this->options[$identifier]:array();
        $converter = new $converterClass($tagOptions);
        return $converter;
    }

    private function convertTag($tag, $converter)
    {
        $ampTag = $converter->convertToAmp($tag);

        $parent = $tag->parentNode;

        if ($ampTag) {
            $parent->replaceChild($ampTag, $tag);
            return;
        }

        if ($this->options['remove_incorrect_tags']) {
            $parent->removeChild($tag);
            return;
        }

    }

    private function deleteTag($tag)
    {
        $parent = $tag->parentNode;
        $parent->removeChild($tag);
    }
}
