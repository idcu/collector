<?php
namespace Common\AdminBundle\Export;

use Exporter\Source\SourceIteratorInterface;
use Exporter\Handler;
use Symfony\Component\HttpFoundation\Response;

class Exporter
{
    public function getResponse($format, $filename, SourceIteratorInterface $source)
    {
        $privateFilename = sprintf('%s/%s', sys_get_temp_dir(), uniqid('sonata_export_', true));

        switch ($format) {
            case 'xls':
                $writer      = new \Exporter\Writer\XlsWriter($privateFilename);
                $contentType = 'application/vnd.ms-excel';
                break;
            case 'xml':
                $writer      = new \Exporter\Writer\XmlWriter($privateFilename);
                $contentType = 'text/xml';
                break;
            case 'json':
                $writer      = new \Exporter\Writer\JsonWriter($privateFilename);
                $contentType = 'application/json';
                break;
            case 'csv':
                $writer      = new \Exporter\Writer\CsvWriter($privateFilename, ',', '"', "", true);
                $contentType = 'text/csv';
                break;
            default:
                throw new \RuntimeException('Invalid format');
        }

        $handler = Handler::create($source, $writer);
        $handler->export();

        $content =  mb_convert_encoding(file_get_contents($privateFilename), 'SJIS-win', 'UTF-8');
        $response = new Response($content, 200, array(
            'Content-Type'        => $contentType,
            'Content-Disposition' => sprintf('attachment; filename=%s', $filename)
        ));

        unlink($privateFilename);

        return $response;
    }
}