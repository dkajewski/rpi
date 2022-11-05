<?php

namespace App\Services;

use App\Repositories\NotesRepository;

class NotesService
{
    private NotesRepository $repository;

    public function __construct(NotesRepository $repository)
    {
        $this->repository = $repository;
    }
}