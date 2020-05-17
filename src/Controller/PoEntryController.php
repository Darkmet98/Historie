<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PoEntryController
{
    private function getFullEntry(string $json) {
        $json = json_decode($json, true);
        return $json["messages"];
    }

    public function getEntry(string $json, int $position) {
        $json = $this->getFullEntry($json);
        $keys = array_keys($json);
        $entry = $keys[$position];
        return ["id"=>$entry, "entry"=>$json[$entry], "size"=>count($json)];
    }
}