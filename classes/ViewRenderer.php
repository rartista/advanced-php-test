<?php

class ViewRenderer
{
    private static function arrayToXML($data, $root_node = null, $xml = null)
    {
        $xml_object = $xml;

        if ($xml_object === null) {
            $xml_object = new SimpleXMLElement($root_node != null ? $root_node : '<root/>');
        }

        foreach ($data as $k => $v) {
            if (is_array($v)) {
                self::arrayToXml($v, $k, $xml_object->addChild($k));
            }  else {
                $xml_object->addChild($k, $v);
            }
        }

        return $xml_object->asXML();
    }

    public static function viewTemplate($data, $action = 'export')
    {
        $content = $data;
        include(PROJECT_ROOT . '/views/' . $action .  '.php');
    }

    public static function viewTable($data)
    {
        if (!$data || !is_array($data) || !count($data)) {
            return self::viewFail('Sorry, no matching data was found');
        }

        $headers = array_keys($data[0]);
        $table_html = '<table>';
        $table_html .= '<thead>';
        $table_html .= '<tr>';

        foreach ($headers as $header) {
            $table_html .= '<th>' . $header . '</th>';
        }

        $table_html .= '</tr>';
        $table_html .= '</thead>';
        $table_html .= '<tbody>';

        foreach ($data as $row) {
            $table_html .= '<tr>';

            foreach ($row as $column) {
                $table_html .= '<td>' . $column . '</td>';
            }

            $table_html .= '</tr>';
        }

        $table_html .= '</tbody>';
        $table_html .= '</table>';

        return $table_html;
    }

    public static function viewFail($message)
    {
        self::viewTemplate('<div class="fail-message">' . $message . '</div>');
    }

    public static function viewSuccess($message)
    {
        self::viewTemplate('<div class="success-message">' . $message . '</div>');
    }

    public static function exportData($data, $format = 'html')
    {
        if ($format == 'html') {
            self::viewTemplate(self::viewTable($data));
        } else {
            switch ($format) {
                case 'xml':
                    header('Content-type: text/xml');
                    echo self::arrayToXML($data, '<data />');
                    break;
                case 'json':
                    header('Content-type: application/json');
                    echo json_encode($data);
                    break;
                case 'csv':
                    header('Content-type: application/csv');
                    header('Content-Disposition: attachment; filename="export.csv";');
                    header('Content-Transfer-Encoding: UTF-8');
                    $fh = fopen('php://output', 'a');
                    fputcsv($fh, array_keys($data[0]));

                    foreach ($data as $d) {
                        fputcsv($fh, array_values($d));
                    }

                    fclose($fh);
                    break;
            }
        }
    }
}
