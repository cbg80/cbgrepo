<?php
/**
 * Declares PostMakerControllerDataProvider class
 *
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
namespace ImageThreadTests\data_providers;

/**
 * Encapsulates methods to provide use case data for test cases
 *
 * @package tests\data_providers
 */
class PostMakerControllerDataProvider
{

    /**
     * Provides use case data for method ImageThreadTests\webapp_controllers\PostMakerControllerTest::testMakePostSuccessful
     *
     * @return array
     */
    public function makePostSuccessfulProvider(): array
    {
        $imgFileName = 'smashTheCovid19.jpg';
        return [
            [
                [
                    'name' => $imgFileName,
                    'tmp_name' => getenv('TMPDIR') . '/' . $imgFileName,
                    'type' => 'image/jpeg',
                    'size' => 106366,
                    'error' => UPLOAD_ERR_OK,
                    'inputTitle' => 'La música extrema apoya a los afectados por la pandemia'
                ]
            ]
        ];
    }

    /**
     * Provides use case data for method ImageThreadTests\webapp_controllers\PostMakerControllerTest::testMakePostFault
     *
     * @return array
     */
    public function makePostFaultProvider(): array
    {
        $videoFileName = 'VID-20150721-WA0000.mp4';
        $pdfFileName = 'bases_plan_cero_particulares.pdf';
        return [
            [
                [
                    'name' => $videoFileName,
                    'tmp_name' => getenv('TMPDIR') . '/' . $videoFileName,
                    'type' => 'video/mp4',
                    'size' => 8799071,
                    'error' => UPLOAD_ERR_INI_SIZE
                ],
                [
                    'code' => UPLOAD_ERR_INI_SIZE,
                    'message' => 'Size of uploaded image exceeds server side limit'
                ]
            ],
            [
                [
                    'name' => $pdfFileName,
                    'tmp_name' => getenv('TMPDIR') . '/' . $pdfFileName,
                    'type' => 'application/pdf',
                    'size' => 276171,
                    'error' => 0
                ],
                [
                    'code' => IMG_THREAD_UPLOAD_ERR_FORBIDDEN['code'],
                    'message' => IMG_THREAD_UPLOAD_ERR_FORBIDDEN['message']
                ]
            ]
        ];
    }
}

