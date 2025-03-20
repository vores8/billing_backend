<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Common\BillingItemUID;

use App\Entity\BillingRepositoryItem;
use App\Entity\UserBillingObject;
use App\Entity\UserBillingData;
// use App\Entity\User;
// use App\Entity\UserBilling;
// use App\Entity\ColocationSingleRack;
// use App\Entity\ColocationStep;

class BillingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $item = new BillingRepositoryItem(BillingItemUID::ColocationSingleRack);
        $item->setTitle('Colocation Single Rack');
        $item->setIsRoot(true);


        $uobject = new UserBillingObject($item);
        $uobject->addUserBillingData(new UserBillingData($item));

        $manager->persist($item);
        $manager->persist($uobject);
        $manager->flush();
        // $item = new ColocationSingleRack();
        // $item->setTitle("Colocation single rack");
        // $item->setIsRoot(true);
        // $manager->persist($item);

        // $item = new ColocationStep();
        // $item->setTitle("Colocation step");
        // $item->setIsRoot(true);
        // $manager->persist($item);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('Colocation type 1');
        // // $billingItem->setIsRoot(true);
        // // $bi1 = $billingItem;
        // // $manager->persist($billingItem);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('Colocation type 2');
        // // $billingItem->setIsRoot(true);
        // // $bi2 = $billingItem;
        // // $manager->persist($billingItem);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('Colocation type 3');
        // // $billingItem->setIsRoot(true);
        // // $bi3 = $billingItem;
        // // $manager->persist($billingItem);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('Electricity 1KW');
        // // $billingItem->setIsRoot(false);
        // // $bi4 = $billingItem;
        // // $manager->persist($billingItem);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('Electricity 2KW');
        // // $billingItem->setIsRoot(false);
        // // $bi5 = $billingItem;
        // // $manager->persist($billingItem);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('Electricity 3KW');
        // // $billingItem->setIsRoot(false);
        // // $bi6 = $billingItem;
        // // $manager->persist($billingItem);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('Internet 100M');
        // // $billingItem->setIsRoot(false);
        // // $bi7 = $billingItem;
        // // $manager->persist($billingItem);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('Internet 1G');
        // // $billingItem->setIsRoot(false);
        // // $bi8 = $billingItem;
        // // $manager->persist($billingItem);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('Internet 10G');
        // // $billingItem->setIsRoot(false);
        // // $bi9 = $billingItem;
        // // $manager->persist($billingItem);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('License Product 1');
        // // $billingItem->setIsRoot(false);
        // // $bi10 = $billingItem;
        // // $manager->persist($billingItem);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('License Product 2');
        // // $billingItem->setIsRoot(false);
        // // $bi11 = $billingItem;
        // // $manager->persist($billingItem);

        // // $billingItem = new BillingItem();
        // // $billingItem->setTitle('License Product 3');
        // // $billingItem->setIsRoot(false);
        // // $bi12 = $billingItem;
        // // $manager->persist($billingItem);

        // // $bi1->addChild($bi4);
        // // $bi1->addChild($bi7);
        // // $bi1->addChild($bi10);

        // // $manager->persist($bi1);

        // // $bi2->addChild($bi5);
        // // $bi2->addChild($bi8);
        // // $bi2->addChild($bi11);

        // // $manager->persist($bi2);

        // // $bi3->addChild($bi6);
        // // $bi3->addChild($bi9);
        // // $bi3->addChild($bi12);

        // // $manager->persist($bi3);

        // // $user = new User();
        // // $user->setTitle('User 1');
        // // $manager->persist($user);
        // // $u1 = $user;

        // // $user = new User();
        // // $user->setTitle('User 2');
        // // $manager->persist($user);
        // // $u2 = $user;

        // // $user = new User();
        // // $user->setTitle('User 3');
        // // $manager->persist($user);
        // // $u3 = $user;

        // // $userBilling = new UserBilling();
        // // $userBilling->setUser($u1);
        // // $userBilling->setRepositoryBilling($bi1);
        // // $manager->persist($userBilling);
        // // $ub1 = $userBilling;

        // // $userBilling = new UserBilling();
        // // $userBilling->setUser($u1);
        // // $userBilling->setRepositoryBilling($bi2);
        // // $manager->persist($userBilling);
        // // $ub2 = $userBilling;

        // // $userBilling = new UserBilling();
        // // $userBilling->setUser($u1);
        // // $userBilling->setRepositoryBilling($bi3);
        // // $manager->persist($userBilling);
        // // $ub3 = $userBilling;

        // // $userBilling = new UserBilling();
        // // $userBilling->setUser($u2);
        // // $userBilling->setRepositoryBilling($bi2);
        // // $manager->persist($userBilling);
        // // $ub4 = $userBilling;

        // // $userBilling = new UserBilling();
        // // $userBilling->setUser($u2);
        // // $userBilling->setRepositoryBilling($bi2);
        // // $manager->persist($userBilling);
        // // $ub5 = $userBilling;

        // // $userBilling = new UserBilling();
        // // $userBilling->setUser($u3);
        // // $userBilling->setRepositoryBilling($bi3);
        // // $manager->persist($userBilling);
        // // $ub6 = $userBilling;

        // $manager->flush();
    }
}
