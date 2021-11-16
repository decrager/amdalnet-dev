<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use App\Entity\Workspace;

class Word extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'word:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'docx read test';

    /**
     * The console command description.
     *
     * @var Workspace
     */
    private $workspace = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->workspace = new Workspace();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $name = basename(__FILE__, '.php');
        $source = __DIR__ . '/../../../docs/UKLUPL-template.docx';

        $result = $this->workspace->importHeadingDocx($source);
        
        var_dump($result);
    }

    /**
     *  Get Element Text
     *
     * @return string|string[]
     */
    protected function getElementText($element)
    {
        if ($element instanceof \PhpOffice\PhpWord\Element\Text) {
            // $style = $element->getParagraphStyle();
            // var_dump('t:', $style);
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
            // $depth = $element->getDepth();
            // var_dump($style, $depth);
            if (!is_string($text)) {
                // var_dump('x',get_class($text));
                return $this->getElementText($text);
            }
            if (!empty($text)) {
                return ['style' => $style, 'text' => $text];
            }
            return null;
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
            return null;
        }

        // if ($element instanceof \PhpOffice\PhpWord\Element\AbstractElement) {
        //     $esult = $element->getText();
        // }
        

        // if (method_exists($element, 'getText')) {
        //     // var_dump($element);
        //     $tmp = $element->getText();
        //     if ($tmp instanceof String) {
        //         $result = $tmp;
        //     } else {
        //         var_dump($element);
        //         // if ($tmp instanceof \PhpOffice\PhpWord\Element\TextRun) {
        //         // }
        //     }
        // }

        return null;
    }
}
