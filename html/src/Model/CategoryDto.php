<?php

namespace App\Model;

final class CategoryDto 
{
    public function __construct(public readonly int $id, public readonly string $name) {

    }   
}