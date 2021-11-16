<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpWord\IOFactory;

class Workspace
{

    /**
     * Import Heading from docx
     *
     * @return array
     */
    public function importHeadingDocx($source)
    {
        $phpWord = IOFactory::load($source);
        $result = [];
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text = $this->getElementText($element);
                if (!empty($text)) {
                    $result[] = $text;
                }
            }
        }
        return $result;
    }

    /**
     *  Get Element Text
     *
     * @return array|null
     */
    protected function getElementText($element)
    {
        if ($element instanceof \PhpOffice\PhpWord\Element\Text) {
            $result = $element->getText();
            if (!is_string($result)) {
                var_dump('tx',$result);
            }
            return $result;
        }

        if ($element instanceof \PhpOffice\PhpWord\Element\Title) {
            $res = null;
            $text = $element->getText();
            $style = $element->getStyle();
            if (!is_string($text)) {
                return $this->getElementText($text);
            }
            if (!empty($text)) {
                return ['style' => $style, 'text' => $text];
            }
        }

        if ($element instanceof \PhpOffice\PhpWord\Element\AbstractContainer) {
            $isHeading = false;
            if (method_exists($element, 'getParagraphStyle')) {
                $style = $element->getParagraphStyle()->getStyleName();
                if (preg_match('/Heading(\d)/', $style)) {
                    $isHeading = true;
                }
            }
            if ($isHeading) {
                $res = [];
                foreach ($element->getElements() as $subElement) {
                    $tmp = $this->getElementText($subElement);
                    // var_dump('s',$tmp);
                    if (!empty($tmp)) {
                        $res[] = $tmp;
                    }
                }
                $result = implode('', $res);
                if (!empty($result)) {
                    return ['style' => $style, 'text' => $result];
                }
            }
        }

        return null;
    }
}
