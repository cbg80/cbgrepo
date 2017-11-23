<?php
/**
 * Declares a service for exporting input data in CSV format
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// src/AppBundle/Service/CSVExporter.php
namespace AppBundle\Service;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Encapsulates a method for exporting input data in CSV format
 */
class CSVExporter
{
    /**
     * Custom serializer for any post
     * @var SerializerInterface
     */
    private $postSerializer;
    /**
     * Sets the custom serializer for any post
     * @param SerializerInterface $postSerializer
     */
    public function __construct(SerializerInterface $postSerializer)
    {
        $this->postSerializer = $postSerializer;
    }
    /**
     * Returns a CSV file that contains the serialized representation of the array of posts got as the first argument
     * @param array $data
     * @param string $schemeAndHttpHost 'http://image-thread.cbg/'
     * @param string $uploadedResourcesDir 'uploads'
     * @return File
     */
    public function export(array $data, string $schemeAndHttpHost, string $uploadedResourcesDir)
    {
        $pathToCSV = tempnam(sys_get_temp_dir(), 'postcsv');
        
        $postsAsCSV = $this->postSerializer->serialize($data, 'csv', array(
            'base_url' => $schemeAndHttpHost
            , 'upload_dir' => $uploadedResourcesDir
        ));
        
        if (file_put_contents($pathToCSV, $postsAsCSV) !== FALSE) {
            $oldPathToCSV = $pathToCSV;
            $pathToCSV = dirname($pathToCSV)
                       . '/'
                       . basename($pathToCSV, '.tmp')
                       . '.csv'
            ;
            rename($oldPathToCSV, $pathToCSV);
            return new File($pathToCSV);
        }
    }
}