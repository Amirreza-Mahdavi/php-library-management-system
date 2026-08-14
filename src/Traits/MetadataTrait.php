<?php

namespace LMS\Traits;

use Exception;

Trait MetadataTrait {

    private string $metaFile = __DIR__ . '/../../data/metadata.json';

    public function getNextId(string $domain): int {
        $matadata = json_decode(file_get_contents($this->metaFile, true));
        $key = "next_{$domain}_id";

        if(!isset($metadata[$key]))
            throw new Exception("Counter '$key' does not exist");

        $id = $metadata[$key];
        $matadata[$key]++;

        file_put_contents(
            $this->metaFile,
            json_encode($metadata, JSON_PRETTY_PRINT),
            LOCK_EX
        );

        return $id;
    }
}