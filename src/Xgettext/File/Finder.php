<?php

namespace Xgettext\File;

use \InvalidArgumentException;
use \RecursiveDirectoryIterator,
    \RecursiveIteratorIterator;

class Finder
{
    public static function findr($path, $extensions = array('hbs'))
    {
        $files = [];

        if (!is_dir($path)) {
            throw new InvalidArgumentException("A valid directory path must be given here");
        }

        $di = new RecursiveDirectoryIterator($path);

        foreach (new RecursiveIteratorIterator($di) as $filename => $file) {

          if($file->isFile() && in_array($file->getExtension(), $extensions)) {
            $files[] = $file->getRealPath();
          }

        }
    
        return $files;
    }
}
