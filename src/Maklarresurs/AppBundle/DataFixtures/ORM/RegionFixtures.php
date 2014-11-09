<?php

namespace Maklarresurs\AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Maklarresurs\AppBundle\Entity\region;

/**
 * Description of StaticData
 *
 */
class RegionFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $repo = $manager->getRepository("MaklarresursAppBundle:Region");

        $path = __DIR__."/../../Resources/fixture-data/regions.json";
        $data = json_decode(file_get_contents($path), true);
        foreach($data as $regionData) {
            $region = $repo->findOneById($regionData['id']);
            if($region == null) {
                $region = new Region();
                $region->setId($regionData['id']);
            }
            $region->setName($regionData['name']);
            $manager->persist($region);
        }
        $manager->flush();
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 1;
    }
}

?>
