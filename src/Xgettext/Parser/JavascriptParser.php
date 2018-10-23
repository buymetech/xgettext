<?php

namespace Xgettext\Parser;

use Xgettext\Poedit\PoeditString;

class JavascriptParser extends AbstractRegexParser implements ParserInterface
{
    public function parse($string = null)
    {
        $line_count = 1;

        $contents   = file_get_contents($this->file);
        $len = mb_strlen($contents);
        $col = 0;

        for($i = 0; $i < $len; $i++)
        {
            $open = null;

            if(mb_substr($contents, $i, 3) == '.t(')
            {
                $chars = $open = '.t(';
                $close = ')';
            }
            else if(mb_substr($contents, $i, 4) == '.tt(')
            {
                $chars = $open = '.tt(';
                $close = ')';
            }
            else 
            {
                $chars = mb_substr($contents, $i, 1);
            }

            if($chars[0] == "\n")
            {
                $line_count++;
                $col = 0;
            }

            if($chars === $open)
            {
                $i = $i + mb_strlen($open);
                
                //ignore whitespace
                $char = mb_substr($contents, $i, 1);
                while ( !in_array($char, ['"', "'"]) )
                {
                    if($char == "\n")
                    {
                         $line_count++;
                         $col = 0;
                    }   

                    $i++;
                    $char = mb_substr($contents, $i, 1);
                }
                $openchar = $char;


                //extract string
                $start = ++$i;
                $char = mb_substr($contents, $start, 1);
                $string = '';

                while( $char != $openchar )
                {
                    $string .= $char;
                    $char = mb_substr($contents, ++$i, 1);
                }
                $end = $i;

                //replace \r\n with \n (normalize)
                $msgid = str_replace(["\r\n", "\t"], ["\n", "    "], $string);
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
               
                continue;
            }

        }

        return $this->strings;
    }
}
