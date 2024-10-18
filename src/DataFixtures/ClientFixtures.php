<?php

namespace App\DataFixtures;
use App\Entity\Client;
use App\Entity\User;
use App\Entity\Dette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setSurname('Nom' . $i);
            $client->setTelephon('0123456' . $i);
            $client->setAddress('Adresse' . $i);
            if ($i % 2 == 0) {
                $user= new User();
                $user->setNom('Nom' . $i);
                $user->setPrenom('Prenom' . $i);
                $user->setLogin('login' . $i);
                $plaintextPassword = 'password';
        
                // hash the password (based on the security.yaml config for the $user class)
                $hashedPassword = $this->encoder->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);
                $client->setUsers($user);
                //creation de dettes
                for ($j = 0; $j < 10; $j++) {
                    $dette = new Dette();
                    $dette->setMontant(1500 * $j);
                    $dette->setCreateAt(new \DateTimeImmutable());
                    $dette->setMontantVerser(1500 * $j);
                    // $dette->setClient($client);
                    // $manager->persist($dette);
                    $client->addDette($dette);
                }
            }else{
                for ($j = 0; $j < 10; $j++) {
                    $dette = new Dette();
                    $dette->setMontant(1500 * $j);
                    $dette->setCreateAt(new \DateTimeImmutable());

                    $dette->setMontantVerser(1500 );
                    // $dette->setClient($client);
                    // $manager->persist($dette);
                    $client->addDette($dette);
                }
            }
            $manager->persist($client);

        }

        $manager->flush();
    }
}
