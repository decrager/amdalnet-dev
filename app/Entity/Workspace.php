<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpWord\IOFactory;

class Workspace
{

    /**
     * JSON Tree from headings
     *
     * @return array
     */
    public function getHeadingTree($headings)
    {
        $result = [];
        $prevdepth = null;
        $previd = null;
        while ($node = $this->getNode($headings)) {
            $result[] = $node;
        }

        return $result;
    }

    protected function getNode(&$headings)
    {
        $matches = array();
        $depth = null;
        $node = array_shift($headings);
        if (!empty($node)) {
            // var_dump($node);
            $node['name'] = $node['text'];
            $node['id'] = uniqid();
            $node['pid'] = '';
            if (preg_match('/Heading(\d)/', $node['style'], $matches)) {
                $depth = $matches[1];
            }
            // var_dump('v', $depth);
            unset($node['text']);
            unset($node['style']);
            $node['children'] = $this->getChilds($headings, $depth, $node['id']);
        }
        return $node;
    }

    protected function getNextNodeDepth($headings)
    {
        $depth = 0;
        if (count($headings)>0) {
            $node = $headings[0];
            if (preg_match('/Heading(\d)/', $node['style'], $matches)) {
                $depth = $matches[1];
            }
        }
        return $depth;
    }

    protected function getChilds(&$headings, $depth=1, $pid='')
    {
        $children = [];
        $depthNode = $this->getNextNodeDepth($headings);
        // var_dump('c', $depth, $depthNode);
        while ($depth < $depthNode) {
            $node = $this->getNode($headings);
            $node['pid'] = $pid;
            $children[] = $node;
            $depthNode = $this->getNextNodeDepth($headings);    
        }

        return $children;
    }

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
