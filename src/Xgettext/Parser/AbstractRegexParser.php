<?php

namespace Xgettext\Parser;

use Xgettext\Poedit\PoeditString;
use Xgettext\Utils\StringIterator;
use Xgettext\Poedit\PoeditPluralString;

abstract class AbstractRegexParser
{
    protected $file;
    protected $keywords;
    protected $strings;

    public function __construct($file)
    {
        $this->file = $file;
        $this->strings = array();
    }

//     public function parse()
//     {
//         $line_count = 1;

//         $contents   = file_get_contents($this->file);
//         $len = mb_strlen($contents);
//         $col = 0;

//         for($i = 0; $i < $len; $i++)
//         {
//             $open = null;

//             if(mb_substr($contents, $i, 3) == '(t ')
//             {
//                 $chars = $open = '(t ';
//                 $close = ')';
//             }
//             else if(mb_substr($contents, $i, 4) ==  '{{t ')
//             {
//                 $chars = $open = '{{t';
//                 $close = '}}';
//             }
//             else {
//                 $chars = mb_substr($contents, $i, 1);
//             }

//             if($chars[0] == "\n")
//             {
//                 $line_count++;
//                 $col = 0;
//             }

//             if($chars === $open)
//             {
//                 $i = $i + mb_strlen($open);
                
//                 //ignore whitespace
//                 $char = mb_substr($contents, $i, 1);
//                 while ( !in_array($char, ['"', "'"]) )
//                 {
//                     if($char == "\n")
//                     {
//                          $line_count++;
//                          $col = 0;
//                     }   

//                     $i++;
//                     $char = mb_substr($contents, $i, 1);
//                 }
//                 $openchar = $char;


//                 //extract string
//                 $start = ++$i;
//                 $char = mb_substr($contents, $start, 1);
//                 $string = '';

//                 while( $char != $openchar )
//                 {
//                     $string .= $char;
//                     $char = mb_substr($contents, ++$i, 1);
//                 }
//                 $end = $i;

//                 //replace \r\n with \n (normalize)
//                 $msgid = str_replace(["\r\n", "\t"], ["\n", "    "], $string);
//                 //replace multiple whitespace with one whitespace
//                 $msgid = preg_replace(["/ {2,}/"], " ", $msgid);
//                 //replace whitespace follow by \n with \n
//                 //replace \n follwed by whitespace with \n
//                 $msgid = preg_replace(["/ {1,}\n/", "/\n {1,}/"], "\n", $msgid);
//                 //escape \n
//                 $msgid = str_replace("\n", '\n', $msgid);

//                 $comment = $this->file . ':' . $line_count;
//                 if(!isset($this->strings[$msgid]))
//                 {
//                     $this->strings[$msgid] = new PoeditString($msgid);
//                 }

//                 $this->strings[$msgid]->addReference($comment);
               
//                 continue;
//             }

//         }

// return $this->strings;
//         //die();
//         $handle = fopen($this->file, "r");



//         // foreach file line by line
//         while ($handle && !feof($handle)) {
//             $line = fgets($handle);
//             $comment = $this->file . ':' . ++$line_count;

//             $calls = $this->extractCalls($line);

//             // nothing found in the parsed line
//             if (empty($calls)) {
//                 continue;
//             }

//             // foreach every call match to analyze arguments, they must be strings
//             foreach ($calls as $call) {
//                 // $arguments = $this->extractArguments($call['arguments']);

//                 // // false positive, no matching arguments inside
//                 // if (empty($arguments)) {
//                 //     continue;
//                 // }

//                 // first argument is msgid
//                 $msgid = $call['keyword'];//str_replace('\\' . $arguments[0]['delimiter'], $arguments[0]['delimiter'], $arguments[0]['arguments']);

//                 // if we did not have found already this string, create it
//                 if (!in_array($msgid, array_keys($this->strings))) {
//                     // we have a plural form case
//                     if (isset($this->keywords[$call['keyword']]) && 2 === count($this->keywords[$call['keyword']])) {
//                         // we asked for a plural keyword above, but only one argument were found. Abort silently
//                         if (!isset($arguments[1])) {
//                             continue;
//                         }

//                         $msgid_plural = str_replace('\\' . $arguments[1]['delimiter'], $arguments[1]['delimiter'], $arguments[$this->keywords[$call['keyword']][1] - 1]['arguments']);
//                         $this->strings[$msgid] = new PoeditPluralString($msgid, $msgid_plural);
//                     } else {
//                         $this->strings[$msgid] = new PoeditString($msgid);
//                     }
//                 }

//                 // add line reference to newly created or already existing string
//                 $this->strings[$msgid]->addReference($comment);
//             }
//         }

//         fclose($handle);

//         return $this->strings;
//     }
}
