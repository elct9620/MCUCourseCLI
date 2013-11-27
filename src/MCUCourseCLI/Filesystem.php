<?php

namespace MCUCourseCLI;

use Illuminate\Filesystem\Filesystem as BaseFilesystem;

class Filesystem extends BaseFilesystem
{
  public function glob($pattern, $flags = 0)
  {
    $pathParts = explode('/', $pattern);
    $pattern = array_pop($pathParts);
    $path = implode('/', $pathParts);
    $pattern = $this->toRegexp($pattern);

    $directoryIterator = new \DirectoryIterator($path);
    $result = array();

    foreach($directoryIterator as $file)
    {
      if($file->isFile()) {
        array_push($result, $file->getPathname());
      }
    }

    return $result;

  }

  /**
   * To Regexp
   *
   * Simple helper to migrate glob pattern to regexp
   */

  protected function toRegexp($pattern)
  {
    $pattern = str_replace('.', '\.', $pattern);
    $pattern = str_replace('*', '.*', $pattern);
    $pattern = "/{$pattern}/";
    return $pattern;
  }
}
