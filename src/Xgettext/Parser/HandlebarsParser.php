<?php

namespace Xgettext\Parser;

use Xgettext\Poedit\PoeditString;

class HandlebarsParser extends AbstractRegexParser implements ParserInterface
{
    protected $startIndex1,$startIndex2,$index,$open,$close;
    public function parse($string = null)
    {
        $line_count = 1;
        $contents       = file_get_contents($this->file);
        $len            = mb_strlen($contents);
        $col            = 0;
        $this->strings  = [];
        $this->index    = 0;
        while ($this->hasTranslations($contents)) {
            //ignore whitespace
            $char = mb_substr($contents, $this->index, 1);
            while ( !in_array($char, ['"', "'"]) ) {
                if($char == "\n") {
                     $line_count++;
                     $col = 0;
                }   
                $this->index++;
                $char = mb_substr($contents, $this->index, 1);
            }
            $openchar   = $char;
            $start      = ++$this->index;
            $char       = mb_substr($contents, $start, 1);
            $string     = '';

            while( $char != $openchar ) {
                $string .= $char;
                $char = mb_substr($contents, ++$this->index, 1);
            }

            //replace \r\n with \n (normalize)
            $msgid = str_replace(["\r\n", "\t"], ["\n", " "], $string);
            //replace multiple whitespace with one whitespace
            $msgid = preg_replace(["/ {2,}/"], " ", $msgid);
            //replace whitespace follow by \n with \n
            //replace \n follwed by whitespace with \n
            $msgid = preg_replace(["/ {1,}\n/", "/\n {1,}/"], "\n", $msgid);
            //escape \n
            //$msgid = str_replace("\n", '\n', $msgid);
            $comment = $this->file . ':' . $line_count;
            if(!isset($this->strings[$msgid]))
            {
                $this->strings[$msgid] = new PoeditString($msgid);
            }

            $this->strings[$msgid]->addReference($comment);
        }

        return $this->strings;
    }

    protected function hasTranslations($contents) {
        
        $this->startIndex1 = mb_strpos($contents, '{{t ',$this->index);
        $this->startIndex2 = mb_strpos($contents, '(t ',$this->index);
        
        if($this->startIndex1 === false && $this->startIndex2 === false) {
            return $this->hasSpecialTranslations($contents);
        }
        $index          = $this->startIndex1;
        $this->open     = '{{t ';
        $this->close    = '}}';
        if($this->startIndex1 === false || ( $this->startIndex2!== false && $this->startIndex2 < $this->startIndex1) ) {
            $this->open     = '(t ';
            $this->close    = ')';
            $index = $this->startIndex2;
        }
        $this->index = $index + mb_strlen($this->open);
        return true;
    }

    protected function hasSpecialTranslations($contents) {
        
        $this->startIndex1 = mb_strpos($contents, '{{tt ',$this->index);
        $this->startIndex2 = mb_strpos($contents, '(tt ',$this->index);
        
        if($this->startIndex1 === false && $this->startIndex2 === false) {
            return false;
        }
        $index          = $this->startIndex1;
        $this->open     = '{{tt ';
        $this->close    = '}}';
        if($this->startIndex1 === false || ( $this->startIndex2!== false && $this->startIndex2 < $this->startIndex1) ) {
            $this->open     = '(tt ';
            $this->close    = ')';
            $index = $this->startIndex2;
        }
        $this->index = $index + mb_strlen($this->open);
        return true;
    }
}
