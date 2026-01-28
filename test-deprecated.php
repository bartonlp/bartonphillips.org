<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

echo "PHP version: ", PHP_VERSION, "\n\n";

class Test {
    #[\Deprecated(message: "This method is deprecated in 8.4+", since: "8.4")]
    public function oldMethod(): void {
        echo "Running oldMethod()\n";
    }
}

$test = new Test();
$test->oldMethod();

echo "Done.\n";
