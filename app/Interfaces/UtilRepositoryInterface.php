<?php

namespace App\Interfaces;

interface UtilRepositoryInterface
{
    public function migrate();
    public function cleanAll();
    public function refreshCaches();
    public function storageCopy();
    public function getCP(string $cp);
}
