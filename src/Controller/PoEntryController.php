<?php

namespace App\Controller;
use App\Entity\Pofile;

class PoEntryController
{
    /**
     * @param string $json
     * @return string
     *
     * This fixes the po generation from the framework and make this very readable
     */
    public function FixJsonGeneration(string $json){
        $json = $this->GetFullEntry($json);
        $poArray = [];

        foreach ($json as $entry=>$text){
            array_push($poArray, $this->GenerateEntry($text, $entry));
        }

        return json_encode($poArray, true);

    }

    /**
     * @param array $entry
     * @param string $name
     * @param string $file
     * @param int $index
     * @param int $size
     * @return array
     *
     * Generate a Json entry for the editor
     */
    public function GenerateEntryJson(array $entry, string $name, string $file, int $index, int $size) {
        return[
            "Project"=>$name,
            "Entry"=>$entry,
            "File"=>$file,
            "Size"=>$size,
            "Index"=>$index
        ];
    }


    /**
     * @param Pofile $po
     * @return array|null
     *
     * Generate a Json entry for the component list
     */
    public function GenerateObjectJson(Pofile $po) {
        return  [
            'Id'=>$po->getId(),
            'Name'=>$po->getName(),
            'Status'=>'Incomplete',
            'Translated'=>40
        ];
    }

    /**
     * @param array $arr
     * @param int $id
     * @return Pofile|null
     *
     * Search the entry
     */
    public function SearchEntry(array $arr, int $id){
        foreach ($arr as $entry){
            if($entry->getId() == $id)
                return $entry;
        }
        return null;
    }

    /**
     * @param array $entry
     * @param string $context
     * @return array
     *
     * Generate a entry for the Po file
     */
    private function GenerateEntry(array $entry, string $context){

        $ori = array_keys($entry)[0]; //Get the original string as a id
        return [
            "Context"=>$context,
            "Original"=>$ori,
            "Translated"=>$entry[$ori]
        ];
    }

    /**
     * @param string $json
     * @return mixed
     *
     * Get the all entries from the generator
     */
    private function GetFullEntry(string $json) {
        $json = json_decode($json, true);
        return $json["messages"];
    }
}