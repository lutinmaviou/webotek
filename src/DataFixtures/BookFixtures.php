<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $book = new Book();
            $book->setTitle("PHP");
            $book->setAuthor("Bertrand Bourion");
            $book->setImg("http://books.google.com/books/content?id=VeVaLcXRtg0C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api");
            $book->setDescription("Hey, hey listen guys. Look, I don't wanna mess with no reefer addicts, okay?
            Yes, definitely, god-dammit George, swear. Okay, so now, you come up, you punch me in the stomach,
            I'm out for the count, right? And you and Lorraine live happily ever after.
            What about George? Hey man, look at Marvin's hand.
            He can't play with his hands like that, and we can't play without him.");

            $manager->persist($book);
        }

        $manager->flush();
    }
}
