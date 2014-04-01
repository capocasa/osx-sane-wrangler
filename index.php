<?php

# Sane Wrangler

# An attempt to make SANE less impossible to install on OSX
# By taking the pain out of version selection

require 'vendor/autoload.php';

$linesFile = __DIR__.'/cache/osxsane/versions';
$base = 'http://www.ellert.se/';

# Populate parameters
$version = isset($_GET['version']) ? $_GET['version']  : null;
$tool = $_GET['tool'];
# Validate parameters
if (null == preg_match('#10\\.[2-9]#', $version)) {
  $version = null;
}
$tools = array('TWAIN-SANE-Interface', 'SANE-Preference-Pane', 'sane-backends', 'libusb');
if (false == in_array($tool, $tools)) {
  $tool = null;
}

# Autodetect
if ($version == null) {
  $bc = new phpbrowscap\Browscap('cache/browscap');
  $b = $bc->getBrowser();
  if ($b->Platform == 'MacOSC') {
    $version = $b->Platform_Version;
  }
}

# Error messages
$errs = array();
if (null == $tool) {  
  $errs[] = 'Chose tool: '. implode(' ', array_map(function ($tool) use ($version) {return "<a href='?tool=$tool&version=$version'>$tool</a>";}, $tools));
}
if (null == $version) {  
  $errs[] = 'You do not appear to be running OSX, so could not autodetect the right version. Please enter it: <form style="display:inline" method="get" action=""><input name="tool" value="'.$tool.'" type="hidden"><input name="version" value="10.6"><input type="submit" value="Go"></form>';
}

# Compile errors

if (count($errs) > 0) {
  echo '<ul style="list-style-type:none;">'.implode('', array_map(function ($err){return "<li>$err</li>";}, $errs)).'</ul>';
  exit();
}

$lines = file($linesFile, FILE_IGNORE_NEW_LINES);

$links = array_filter($lines, function ($line) use ($version, $tool) { "$tool $version $line"; return strpos($line, $version) !== false && strpos($line, $tool) !== false; });

$link = reset($links); # newest

header("Location: $base/$link");

