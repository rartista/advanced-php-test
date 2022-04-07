<?php

class ReportController extends BaseController
{
    public function run()
    {
        $model = new BaseModel();
        $sql = 'SELECT roster.name, team.name, player_totals.age, roster.number, roster.pos, ((player_totals.3pt / player_totals.3pt_attempted) * 100) AS 3pt_percentage, player_totals.3pt ';
        $sql .= 'FROM player_totals INNER JOIN roster ON player_totals.player_id = roster.id INNER JOIN team ON roster.team_code = team.code ';
        $sql .= 'WHERE player_totals.age > 30 AND (player_totals.3pt / player_totals.3pt_attempted) > 0.35 ';
        $sql .= 'ORDER BY 3pt_percentage DESC';
        $best_3pt = $model->runQuery($sql, []);
        $best_3pt = ViewRenderer::viewTable($best_3pt);

        $model = new BaseModel();
        $sql = 'SELECT  team.name, CONCAT(ROUND(((SUM(player_totals.3pt) / SUM(player_totals.3pt_attempted)) * 100), 2), "%") AS team_3pt_percentage, SUM(player_totals.3pt) AS team_3pt, SUM(player_totals.3pt > 0) AS contributing_players, SUM(player_totals.3pt_attempted > 0) AS attempted_players, SUM(CASE WHEN player_totals.3pt = 0 AND player_totals.3pt_attempted > 0 THEN player_totals.3pt_attempted ELSE 0 END) AS team_total_non_3pt_attempters ';
        $sql .= 'FROM team INNER JOIN roster ON team.code = roster.team_code INNER JOIN player_totals ON roster.id = player_totals.player_id ';
        $sql .= 'GROUP BY roster.team_code';
        $team_3pt = $model->runQuery($sql, []);
        $team_3pt = ViewRenderer::viewTable($team_3pt);

        ViewRenderer::viewTemplate([
            'best_3pt' => $best_3pt,
            'team_3pt' => $team_3pt,
        ], 'report');
    }
}
