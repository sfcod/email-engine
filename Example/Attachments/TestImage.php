<?php

namespace SfCod\EmailEngineBundle\Example\Attachments;

use SfCod\EmailEngineBundle\Example\TestTemplateOptions;
use SfCod\EmailEngineBundle\Template\Attachments\AbstractAttachment;

/**
 * Class TestAttachment
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Example\Attachments
 *
 * @property TestTemplateOptions $options
 */
class TestImage extends AbstractAttachment
{
    /**
     * Get attachment name
     *
     * @return string
     */
    public function getFileName(): string
    {
        return 'test_file.php';
    }

    /**
     * Get attachment path
     *
     * @return string
     */
    public function getFileContent(): string
    {
        return file_get_contents($this->options->filePath);
    }
}
