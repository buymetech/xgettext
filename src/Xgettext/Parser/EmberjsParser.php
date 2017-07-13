<?php

namespace Xgettext\Parser;

class EmberjsParser extends AbstractRegexParser implements ParserInterface
{
    public function extractCalls($line)
    {
        $matches1 = [];
        $matches2 = [];
        $calls = array();
        //build pattern
        $pattern1 = '/\.t\(\s*["](.*)["](.*)\)/U';
        preg_match_all($pattern1, $line, $matches1);
        foreach ($matches1[0] as $index => $keyword) {
            $calls[] = array(
                'keyword'   => $matches1[1][$index],
                'arguments' => $matches1[2][$index],
            );
        }

        $pattern2 =  '/\.t\(\s*[\'](.*)[\'](.*)\)/U';
        preg_match_all($pattern2, $line, $matches2);
        foreach ($matches2[0] as $index => $keyword) {
            $calls[] = array(
                'keyword'   => $matches2[1][$index],
                'arguments' => $matches2[2][$index],
            );
        }

        return $calls;
    }

    public function extractArguments($arguments)
    {
        $args = array();
        preg_match_all('`(["\'])(.*?)\1`', $arguments, $matches);

        foreach ($matches[1] as $index => $delimiter) {
            $args[] = array(
                'delimiter' => $delimiter,
                'arguments'  => $matches[2][$index],
            );
        }

        return $args;
    }
}
