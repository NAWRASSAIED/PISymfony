<?php

namespace App\Test\Controller;

use App\Entity\Reservations;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ReservationRepository $repository;
    private string $path = '/reservationsajout/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Reservations::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'reservation[cinclient]' => 'Testing',
            'reservation[nomclient]' => 'Testing',
            'reservation[nombrepersonnes]' => 'Testing',
            'reservation[datedebut]' => 'Testing',
            'reservation[datefin]' => 'Testing',
            'reservation[modePaiement]' => 'Testing',
            'reservation[typehebergement]' => 'Testing',
            'reservation[typeactivite]' => 'Testing',
            'reservation[numtel]' => 'Testing',
        ]);

        self::assertResponseRedirects('/reservationsajout/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservations();
        $fixture->setCinclient('My Title');
        $fixture->setNomclient('My Title');
        $fixture->setNombrepersonnes('My Title');
        $fixture->setDatedebut('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setModePaiement('My Title');
        $fixture->setTypehebergement('My Title');
        $fixture->setTypeactivite('My Title');
        $fixture->setNumtel('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservations();
        $fixture->setCinclient('My Title');
        $fixture->setNomclient('My Title');
        $fixture->setNombrepersonnes('My Title');
        $fixture->setDatedebut('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setModePaiement('My Title');
        $fixture->setTypehebergement('My Title');
        $fixture->setTypeactivite('My Title');
        $fixture->setNumtel('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reservation[cinclient]' => 'Something New',
            'reservation[nomclient]' => 'Something New',
            'reservation[nombrepersonnes]' => 'Something New',
            'reservation[datedebut]' => 'Something New',
            'reservation[datefin]' => 'Something New',
            'reservation[modePaiement]' => 'Something New',
            'reservation[typehebergement]' => 'Something New',
            'reservation[typeactivite]' => 'Something New',
            'reservation[numtel]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reservationsajout/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCinclient());
        self::assertSame('Something New', $fixture[0]->getNomclient());
        self::assertSame('Something New', $fixture[0]->getNombrepersonnes());
        self::assertSame('Something New', $fixture[0]->getDatedebut());
        self::assertSame('Something New', $fixture[0]->getDatefin());
        self::assertSame('Something New', $fixture[0]->getModePaiement());
        self::assertSame('Something New', $fixture[0]->getTypehebergement());
        self::assertSame('Something New', $fixture[0]->getTypeactivite());
        self::assertSame('Something New', $fixture[0]->getNumtel());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Reservations();
        $fixture->setCinclient('My Title');
        $fixture->setNomclient('My Title');
        $fixture->setNombrepersonnes('My Title');
        $fixture->setDatedebut('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setModePaiement('My Title');
        $fixture->setTypehebergement('My Title');
        $fixture->setTypeactivite('My Title');
        $fixture->setNumtel('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/reservationsajout/');
    }
}
