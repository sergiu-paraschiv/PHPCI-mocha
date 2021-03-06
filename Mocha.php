<?php

namespace SergiuParaschiv\PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Plugin\Util\TapParser;

class Mocha implements \PHPCI\Plugin
{
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->directory = '';
        $this->command = '';
        $this->data_offset = 0;

        if (isset($options['directory'])) {
            $this->directory = $options['directory'];
        }

        if (isset($options['command'])) {
            $this->command = $options['command'];
        }

        if (isset($options['data_offset'])) {
            $this->data_offset = $options['data_offset'];
        }
    }

    public function execute()
    {
        if (empty($this->command)) {
            $this->phpci->logFailure('Configuration command not found.');
            return false;
        }

        if (empty($this->directory)) {
            $this->phpci->logFailure('Configuration directory not found.');
            return false;
        }

        $this->phpci->logExecOutput(false);

        $cmd = 'cd ' . $this->directory . '; ' . $this->command;
        $this->phpci->executeCommand($cmd);

        $tapString = $this->phpci->getLastOutput();

        if($this->data_offset) {
            $tapString = implode("\n", array_slice(explode("\n", $tapString), $this->data_offset));
        }

        $tapString = mb_convert_encoding($tapString, "UTF-8", "ISO-8859-1");

        $tapString = 'TAP version 13' . "\n" . $tapString;

        try {
            $tapParser = new TapParser($tapString);
            $output = $tapParser->parse();
        } catch (\Exception $ex) {
            $this->phpci->logFailure($tapString);
            throw $ex;
        }

        $failures = $tapParser->getTotalFailures();

        $success = ($failures === 0);

        $this->build->storeMeta('mocha-errors', $failures);
        $this->build->storeMeta('mocha-data', $output);

        $this->phpci->logExecOutput(true);

        return $success;
    }
}
