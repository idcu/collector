<?php
namespace Collector\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Collector\EntityBundle\Entity\Staff;
use Collector\EntityBundle\Entity\StaffGroup;

class LoadStaffData implements FixtureInterface{

    public function load(ObjectManager $manager)
    {
        $staffAdmin = new Staff();
        $staffAdmin->setUsername('test');
        $staffAdmin->setLastName('テスト');
        $staffAdmin->setFirstName('テスト');
        $staffAdmin->setEmail('test@example.com');
        $staffAdmin->setPlainPassword('test');
        $staffAdmin->setEnabled(true);

        $manager->persist($staffAdmin);
        $manager->flush();
    }

}