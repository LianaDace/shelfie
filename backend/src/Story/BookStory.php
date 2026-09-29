<?php

namespace App\Story;

use App\Factory\BookFactory;
use Zenstruck\Foundry\Story;

final class BookStory extends Story
{
    public function build(): void
    {
        BookFactory::new()->many(100)->create();
    }
}
