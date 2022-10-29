<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

	public function findAllLoverThanPrice(int $price): array
	{
		$entityManager = $this->getEntityManager();

		$query = $entityManager->createQuery(
			'SELECT p
            FROM App\Entity\Product p
            WHERE p.price < :price
            ORDER BY p.price ASC'
		)->setParameter('price', $price);

		// returns an array of Product objects
		return $query->getResult();
	}

	public function findAllGreaterThanPriceDql(int $price, bool $includeUnavailableProducts = false): array
	{
		// automatically knows to select Products
		// the "p" is an alias you'll use in the rest of the query
		$qb = $this->createQueryBuilder('p')
			->andWhere('p.price > :price')
			->setParameter('price', $price)
			->orderBy('p.price', 'ASC');

		if (!$includeUnavailableProducts) {
			$qb->andWhere('p.active = TRUE');
		}

		$query = $qb->getQuery();

		return $query->execute();

		// to get just one result:
		// $product = $query->setMaxResults(1)->getOneOrNullResult();
	}

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
