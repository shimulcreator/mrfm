<?php

namespace Maklarresurs\AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Maklarresurs\AppBundle\Entity\Area;
use Maklarresurs\AppBundle\Entity\Region;


class ZoneFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $path = __DIR__."/../../Resources/fixture-data/zones.json";
        $data = json_decode(file_get_contents($path), true);



        $createAreas = function($areas, Area $parent=null, Region $region) use ($manager, &$createAreas) {
            foreach($areas as $areaData) {
                $area = new Area();
                $area->setRegion($region);
                $area->setName($areaData['name']);
                if(isset($areaData['mapRef'])){
                    $area->setMapRef($areaData['mapRef']);
                }
                if($parent) {
                    $area->setParentArea($parent);
                }

                $manager->persist($area);

                if(isset($areaData['areas'])) $createAreas($areaData['areas'], $area, $region);
            }
        };


        foreach($data as $regionAreas) {
            $region = $manager->getReference("MaklarresursAppBundle:Region", $regionAreas['region']);

            $createAreas($regionAreas['areas'], null, $region);

            $manager->flush();
            $manager->clear();
        }


    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 2;
    }
}

?>
