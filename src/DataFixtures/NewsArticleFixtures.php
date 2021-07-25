<?php

namespace App\DataFixtures;

use App\Entity\NewsArticle;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $newsArticle = new NewsArticle();
        $newsArticle->setCreatedAt(new DateTimeImmutable('2021-07-02'));
        $newsArticle->setTitle('Apéroooooooooooooo!!!');
        $newsArticle->setContent('<p>Le <strong>13 juillet 2021</strong> aura lieu le traditionnel BBQ chez Lolo,
        notre bien aimé chef d\'orchestre! Ce sera l\'occasion de se retrouver
        après une année vide d\'événements <i>big-band-esques</i>,
        et de discuter de l\'année à venir, qui nous l\'espérons sera bien plus fructueuse!</p><p>
        Surveillez donc bien les actus dans les mois qui viennent, et à la vôtre!</p>
        ');
        $manager->persist($newsArticle);

        $manager->flush();
    }
}
