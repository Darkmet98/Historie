<?php

namespace App\Controller;
use App\Entity\PoFile;

class PoEntryController
{
    /**
     * @param string $json
     * @return array
     *
     * This fixes the po generation from the framework and make this very readable
     */
    public function FixJsonGeneration(string $json){
        $json = $this->GetFullEntry($json);
        $poArray = [];

        foreach ($json as $entry=>$text){
            array_push($poArray, $this->GenerateEntry($text, $entry));
        }

        return $poArray;

    }

    /**
     * @param array $entry
     * @param array $previousEntry
     * @param array $nextEntry
     * @param string $name
     * @param string $file
     * @param int $index
     * @param int $size
     * @param $visualizator
     * @return array
     *
     * Generate a Json entry for the editor
     */
    public function GenerateEntryJson(array $entry, array $previousEntry, array $nextEntry, string $name, string $file, int $index, int $size, $visualizator) {
        return[
            "Project"=>$name,
            "CurrentEntry"=>$entry,
            "NextEntry"=>$nextEntry,
            "PreviousEntry"=>$previousEntry,
            "File"=>$file,
            "Size"=>$size,
            "Index"=>$index,
            "Visualizator"=>$this->GetVisualizator($visualizator)
        ];
    }


    /**
     * @param PoFile $po
     * @return array|null
     *
     * Generate a Json entry for the component list
     */
    public function GenerateObjectJson(PoFile $po) {
        $count = $this->CheckTranslatedEntries($po);
        return  [
            'Id'=>$po->getId(),
            'Name'=>$po->getName(),
            'Status'=>$this->CheckTranslationStatus($count),
            'Translated'=>$count
        ];
    }

    /**
     * @param PoFile $po
     * @return false|float|void
     *
     * Calculate the percentage of translated entries
     */
    private function CheckTranslatedEntries(PoFile $po) {
        $translated = 0;
        foreach ($po->getEntries() as $entry) {
            if($entry["Translated"] != null)
                $translated++;
        }
        return round(($translated / count($po->getEntries()) * 100), 2,PHP_ROUND_HALF_UP);
    }

    /**
     * @param $count
     * @return string
     *
     * Check the translation status
     */
    private function CheckTranslationStatus($count){
        if($count == 0)
            return "Not started";
        else if($count == 100)
            return "Finished";
        else
            return "In progress";
    }

    /**
     * @param array $arr
     * @param int $id
     * @return PoFile|null
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

    /**
     * @param $visualizator
     * @return array
     *
     * Return a json from the visualizator
     */
    private function GetVisualizator($visualizator){
        if ($visualizator != null)
            return [
            "ImageFile"=>$visualizator->getBackground(),
            "FontSize"=>$visualizator->getFontSize(),
            "LineHeight"=>$visualizator->getLineHeight(),
            "TopPosition"=>$visualizator->getTopPosition(),
            "LeftPosition"=>$visualizator->getLeftPosition()
        ];

        return [
            "ImageFile"=>"NONE",
            "FontSize"=>0,
            "LineHeight"=>0,
            "TopPosition"=>0,
            "LeftPosition"=>0
        ];
    }
}