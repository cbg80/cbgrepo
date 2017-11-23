<?php
/**
 * Declares PostRepository class
 *
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// src/AppBundle/Repository/PostRepository.php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Encapsulates methods to query Post objects
 */
class PostRepository extends EntityRepository
{
    /**
     * Returns the total number of registered posts
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function countTotals()
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT COUNT(p.id) FROM AppBundle:Post p'
            )
        ;
        
        return $query->getSingleScalarResult();
    }
    /**
     * Returns all the registered posts ordered by timestamp
     * @return array
     */
    public function getAll()
    {
        return $this->findBy(array(), array('timestamp' => 'DESC'));
    }
}