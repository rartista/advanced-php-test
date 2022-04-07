<?php

class PlayerTotals extends BaseModel
{
    public function __construct()
    {
        $this->table = 'player_totals';
    }

    public function getPlayerStats($params = [])
    {
        unset($params['format']);
        unset($params['type']);
        $sql = 'SELECT roster.name, player_totals.* ';
        $sql .= 'FROM ' . $this->table . ' INNER JOIN roster ON player_totals.player_id = roster.id ';

        $statement_params = [];

        if (count($params)) {
            $sql .= 'WHERE ';
            $wheres = [];

            foreach ($params as $key => $value) {
                if ($key == 'playerId') {
                    $wheres[] = 'roster.id = :id';
                    $statement_params[':id'] = $value;
                } elseif ($key == 'player') {
                    $wheres[] = 'roster.name = :name';
                    $statement_params[':name'] = $value;
                } elseif ($key == 'team') {
                    $wheres[] = 'roster.team_code = :team_code';
                    $statement_params[':team_code'] = $value;
                } elseif ($key == 'position') {
                    $wheres[] = 'roster.pos = :pos';
                    $statement_params[':pos'] = $value;
                } elseif ($key == 'country') {
                    $wheres[] = 'roster.nationality = :nationality';
                    $statement_params[':nationality'] = $value;
                }
            }

            $sql .= implode(' AND ', $wheres);
        }

        $player_stats = $this->runQuery($sql, $statement_params);

        if (count($player_stats)) {
            foreach ($player_stats as $key => $value) {
                unset($player_stats[$key]['player_id']);
                $player_stats[$key]['total_points'] = ($player_stats[$key]['3pt'] * 3) + ($player_stats[$key]['2pt'] * 2) + $player_stats[$key]['free_throws'];
                $player_stats[$key]['field_goals_pct'] = $player_stats[$key]['field_goals_attempted'] ? (round($player_stats[$key]['field_goals'] / $player_stats[$key]['field_goals_attempted'], 2) * 100) . '%' : 0;
                $player_stats[$key]['3pt_pct'] = $player_stats[$key]['3pt_attempted'] ? (round($player_stats[$key]['3pt'] / $player_stats[$key]['3pt_attempted'], 2) * 100) . '%' : 0;
                $player_stats[$key]['2pt_pct'] = $player_stats[$key]['2pt_attempted'] ? (round($player_stats[$key]['2pt'] / $player_stats[$key]['2pt_attempted'], 2) * 100) . '%' : 0;
                $player_stats[$key]['free_throws_pct'] = $player_stats[$key]['free_throws_attempted'] ? (round($player_stats[$key]['free_throws'] / $player_stats[$key]['free_throws_attempted'], 2) * 100) . '%' : 0;
                $player_stats[$key]['total_rebounds'] = $player_stats[$key]['offensive_rebounds'] + $player_stats[$key]['defensive_rebounds'];
            }
        }

        return $player_stats;
    }

    public function getPlayers($params = [])
    {
        unset($params['format']);
        unset($params['type']);
        $sql = 'SELECT roster.* ';
        $sql .= 'FROM  roster ';

        $statement_params = [];

        if (count($params)) {
            $sql .= 'WHERE ';
            $wheres = [];

            foreach ($params as $key => $value) {
                if ($key == 'playerId') {
                    $wheres[] = 'roster.id = :id';
                    $statement_params[':id'] = $value;
                } elseif ($key == 'player') {
                    $wheres[] = 'roster.name = :name';
                    $statement_params[':name'] = $value;
                } elseif ($key == 'team') {
                    $wheres[] = 'roster.team_code = :team_code';
                    $statement_params[':team_code'] = $value;
                } elseif ($key == 'position') {
                    $wheres[] = 'roster.pos = :pos';
                    $statement_params[':pos'] = $value;
                } elseif ($key == 'country') {
                    $wheres[] = 'roster.nationality = :nationality';
                    $statement_params[':nationality'] = $value;
                }
            }

            $sql .= implode(' AND ', $wheres);
        }

        return $this->runQuery($sql, $statement_params);
    }
}
