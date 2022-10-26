<?php

/**
 * Utils extend php word templater
 */

namespace App\Utils;

use PhpOffice\PhpWord\Element\TextBox;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\TemplateProcessor as OriginalProcessor;
use PhpOffice\PhpWord\Writer\Word2007\Element\Container;

class TemplateProcessor extends OriginalProcessor
{
    /**
     * Clone a block.
     *
     * @param string $blockname
     * @param int $clones How many time the block should be cloned
     * @param bool $replace
     * @param bool $indexVariables If true, any variables inside the block will be indexed (postfixed with #1, #2, ...)
     * @param array $variableReplacements Array containing replacements for macros found inside the block to clone
     *
     * @return string|null
     */
    public function cloneBlock($blockname, $clones = 1, $replace = true, $indexVariables = false, $variableReplacements = null)
    {
        $idx_tag = mb_strpos($this->tempDocumentMainPart, '${' . $blockname . '}');

        if ($idx_tag === false)
            return null;

        $idx_start = mb_strrpos(mb_substr($this->tempDocumentMainPart, 0, $idx_tag), '<w:p ');
        $idx_end   =  mb_strpos($this->tempDocumentMainPart,               '${/' . $blockname . '}', $idx_tag);


        if ($idx_start === false || $idx_end === false)
            return null;

        $idx_end = mb_strpos($this->tempDocumentMainPart, '</w:p>', $idx_end);

        if ($idx_end === false)
            return null;

        $idx_end += 6;

        $what = mb_substr($this->tempDocumentMainPart, $idx_start, $idx_end - $idx_start);

        // --- //

        $idx_content_start =   mb_strpos($what, 'p>');
        $idx_content_end   =  mb_strrpos($what, '<w:p ');

        if ($idx_content_start === false || $idx_content_end === false)
            return null;

        $idx_content_start += 2;

        $xmlBlock = mb_substr($what, $idx_content_start, $idx_content_end - $idx_content_start);

        // --- //

        if ($replace) {
            $by = array();

            if ($indexVariables)
                $by = $this->indexClonedVariables($clones, $xmlBlock);
            elseif ($variableReplacements !== null && is_array($variableReplacements))
                $by = $this->replaceClonedVariables($variableReplacements, $xmlBlock);
            else
                for ($i = 1; $i <= $clones; $i++)
                    $by[] = $xmlBlock;

            $by = implode('', $by);

            $this->tempDocumentMainPart = str_replace($what, $by, $this->tempDocumentMainPart);
        }

        return $xmlBlock;
    }

    /**
     * Replaces a macro block with the given HTML markup.
     *
     * PhpWord's variables replacing doesn't allow to use HTML markup as
     * macro replacement content.
     *
     * This method is a workaround that uses the PhpWord Html service to
     * parse Html into PhpWord elements, adds them as children to a
     * container element (TextBox), then uses the Container writer to
     * write its children elements only.
     *
     * @param string $search
     *   The macro (variable) name.
     * @param string $markup
     *   The HTML markup as a string.
     */
    public function setHtmlBlockValue($search, $markup)
    {
        // Create a dummy container element for the content.
        $wrapper = new TextBox();

        // Parse the given HTML markup and add it as child elements
        // to the container.
        Html::addHtml($wrapper, $markup);

        // Render the child elements of the container.
        $xmlWriter = new XMLWriter();
        $containerWriter = new Container($xmlWriter, $wrapper, false);
        $containerWriter->write();

        // Replace the macro parent block with the rendered contents.
        $this->replaceXmlBlock($search, $xmlWriter->getData(), 'w:p');
    }
}