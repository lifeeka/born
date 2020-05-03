<?php

$pharFile = 'born.phar';

// clean up
if (file_exists("born")) 
{
    unlink("born");
}

if (file_exists($pharFile . '.gz')) 
{
    unlink($pharFile . '.gz');
}

// create phar
$phar = new Phar($pharFile);
$phar->startBuffering();
$defaultStub = $phar->createDefaultStub('application');
$phar->buildFromDirectory( '../born-php','/^((?!\.born|.idea|.git|make|tests).)*$/'); 
$phar->setStub($defaultStub);
$phar->stopBuffering(); 
$phar->compressFiles(Phar::GZ);
rename( 'born.phar', 'born'); 
chmod( 'born', 0770); 

echo "$pharFile successfully created" . PHP_EOL;