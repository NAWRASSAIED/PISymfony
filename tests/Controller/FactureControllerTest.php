<?php

namespace App\Test\Controller;

use App\Entity\Facture;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FactureControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private FactureRepository $repository;
    private string $path = '/facture/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Facture::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Facture index');

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
            'facture[numfacture]' => 'Testing',
            'facture[montantFacture]' => 'Testing',
            'facture[datePaiement]' => 'Testing',
            'facture[numres]' => 'Testing',
        ]);

        self::assertResponseRedirects('/facture/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Facture();
        $fixture->setNumfacture('My Title');
        $fixture->setMontantFacture('My Title');
        $fixture->setDatePaiement('My Title');
        $fixture->setNumres('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Facture');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Facture();
        $fixture->setNumfacture('My Title');
        $fixture->setMontantFacture('My Title');
        $fixture->setDatePaiement('My Title');
        $fixture->setNumres('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'facture[numfacture]' => 'Something New',
            'facture[montantFacture]' => 'Something New',
            'facture[datePaiement]' => 'Something New',
            'facture[numres]' => 'Something New',
        ]);

        self::assertResponseRedirects('/facture/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNumfacture());
        self::assertSame('Something New', $fixture[0]->getMontantFacture());
        self::assertSame('Something New', $fixture[0]->getDatePaiement());
        self::assertSame('Something New', $fixture[0]->getNumres());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Facture();
        $fixture->setNumfacture('My Title');
        $fixture->setMontantFacture('My Title');
        $fixture->setDatePaiement('My Title');
        $fixture->setNumres('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/facture/');
    }
}
