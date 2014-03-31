<?php


# Sane Wrangler

# An attempt to make SANE less impossible to install on OSX
# By taking the pain out of version selection

require 'vendor/autoload.php';

# Detect OSX version

$bc = new phpbrowscap\Browscap('cache/browscap');

$b = $bc->getBrowser();

$err = array();

$version = isset($_GET['version']) ? $_GET['version']  : $b->Platform_Version;

if (null == preg_match('#10\\.[2-9]#', $version)) {
  $version = null;
  $err[] = 'Use on OSX or enter version <form style="display:inline" method="get" action=""><input name="version" value="10.6"><input type="submit" value="Go"></form>';
}

if (count($err) > 0) {
  echo '<ul style="list-style-type:none;">'.implode('', array_map(function ($err){return "<li>$err</li>";}, $err)).'</ul>';
  exit();
}



