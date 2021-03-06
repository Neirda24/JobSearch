<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Company;
use AppBundle\Entity\Search;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * CompanyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CompanyRepository extends EntityRepository
{
    /**
     * @param Search $search
     *
     * @return Company[]
     */
    public function fetchGroupedForDashboard(Search $search)
    {
        $qb = $this->createQueryBuilder('c');
        
        $qb
            ->select('DISTINCT c')
            ->addSelect('sd')
            ->leftJoin('c.searchDetails', 'sd')
            ->leftJoin('sd.search', 's')
            ->andWhere($qb->expr()->eq('s', ':search'))
            ->setParameter('search', $search)
            ->orderBy('IFNULL(sd.createdAt, c.createdAt)', 'DESC');
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * @param Search $search
     *
     * @return QueryBuilder
     */
    public function excludeFromSearchQB(Search $search)
    {
        $companyIdsQb = $this->createQueryBuilder('c');
    
        $companyIds = $companyIdsQb
            ->select('c.id')
            ->leftJoin('c.searchDetails', 'sd')
            ->leftJoin('sd.search', 's')
            ->where($companyIdsQb->expr()->eq('s', ':search'))
            ->setParameter('search', $search)
            ->groupBy('c.id')
            ->getQuery()
            ->getResult()
        ;
    
        $qb = $this->createQueryBuilder('c');
        
        if (count($companyIds) > 0) {
            $qb
                ->where($qb->expr()->notIn('c.id', ':companyIds'))
                ->setParameter('companyIds', $companyIds);
        }
        
        return $qb;
    }
}
