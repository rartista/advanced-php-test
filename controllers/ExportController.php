<?php

class ExportController extends BaseController
{
    private function rewriteDataForXML($data)
    {
        $spelled_keys = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        $xml_data = [];

        foreach ($data as $row_key => $row) {
            $xml_row = [];

            foreach ($row as $key => $value) {
                $key = preg_replace_callback('(\d)', function($matches) use ($spelled_keys) {
                    return $spelled_keys[$matches[0]] . '_';
                }, $key);
                
                $xml_row[rtrim($key, '_')] = $value;
            }

            $row_key = preg_replace_callback('(\d)', function($matches) use ($spelled_keys) {
                return $spelled_keys[$matches[0]] . '_';
            }, $row_key);
            $xml_data[rtrim($row_key, '_')] = $xml_row;
        }

        return ['entry' => $xml_data];
    }

    private function playerstats()
    {
        $player_totals = new PlayerTotals();
        $format = array_key_exists('format', $this->args) ? $this->args['format'] : 'html';
        $result = $player_totals->getPlayerStats($this->args);

        if ($result && $format == 'xml') {
            $result = $this->rewriteDataForXML($result);
        }

        ViewRenderer::exportData($result, $format);
    }

    private function players()
    {
        $player_totals = new PlayerTotals();
        $format = array_key_exists('format', $this->args) ? $this->args['format'] : 'html';
        $result = $player_totals->getPlayers($this->args);

        if ($result && $format == 'xml') {
            $result = $this->rewriteDataForXML($result);
        }

        ViewRenderer::exportData($result, $format);
    }

    public function run()
    {
        if ($this->args['type'] == 'playerstats') {
            $this->playerstats();
        } elseif ($this->args['type'] == 'players') {
            $this->players();
        }
    }
}
