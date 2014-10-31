<?php

namespace JimLind\TiVo;

use JimLind\TiVo\Utilities;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

/**
 * Decode is a service for decoding raw TiVo video files.
 */
class Decode
{
    /**
     * @var string
     */
    private $mak;

    /**
     * @var Symfony\Component\Process\Process
     */
    protected $process = null;

    /**
     * @var Psr\Log\LoggerInterface
     */
    protected $logger  = null;

    /**
     * Constructor
     *
     * @param string                            $mak    Your TiVo's Media Access Key.
     * @param Symfony\Component\Process\Process $process The Symfony Process Component.
     * @param Psr\Log\LoggerInterface           $logger  A PSR-0 Logger.
     */
    public function __construct($mak, Process $process, LoggerInterface $logger = null)
    {
        $this->mak     = $mak;
        $this->process = $process;
        $this->logger  = $logger;
    }

    /**
     * Decode a TiVo file and write the new file to the new location.
     *
     * @param string $input  Where the raw TiVo file is.
     * @param string $output Where the decode Mpeg file goes.
     */
    public function decode($input, $output)
    {
        if (!$this->checkDecoder()) {
            $warning = 'The tivodecode tool can not be trusted or found. ';
            Utilities\Log::warn($warning, $this->logger);
            // Exit early.
            return false;
        }

        $command = 'tivodecode ' . $input . ' -m ' . $this->mak . ' -n -o ' . $output;

        $this->process->setCommandLine($command);
        $this->process->run();
    }

    /**
     * Check if a reasonable version of TiVo File Decoder is installed.
     *
     * Oddly enough the version information is written to the error output.
     *
     * @return boolean
     */
    protected function checkDecoder()
    {
        $command = 'tivodecode --version';

        $this->process->setCommandLine($command);
        $this->process->setTimeout(1); // 1 second
        $this->process->run();

        $output = $this->process->getErrorOutput();
        // TiVo File Decoder reports "Copyright (c) 2006-2007, Jeremy Drake"
        return strpos($output, 'Jeremy Drake') !== false;
    }
}