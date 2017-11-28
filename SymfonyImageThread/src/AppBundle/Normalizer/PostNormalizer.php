<?php
/**
 * Declares the Post normalizer
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// src/AppBundle/Normalizer/PostNormalizer.php
namespace AppBundle\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use AppBundle\Entity\Post;

/**
 * Encapsulates the normalization of any Post for downloading as CSV
 */
class PostNormalizer implements NormalizerInterface
{

    /**
     * Checks whether the object given as the first argument is supported for normalization by this normalizer.
     *
     * @param mixed $data Data to normalize
     * @param string $format The format being (de-)serialized from or into
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Post;
    }

    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param object $object object to normalize
     * @param string $format format the normalization result will be encoded as
     * @param array $context Context options for the normalizer
     *
     * @return mixed
     */
    public function normalize($object, $format = null, array $context = array())
    {
        /* @var $object Post */
        return array(
            'title' => $object->getTitle()
            , 'image' => $context['base_url'] . '/'. $context['upload_dir']. '/' . $object->getImage()->getBasename()
            , 'timestamp' => $object->getTimestamp()->format('d-m-Y H:i:s')
        )
        ;
    }
}