<?php

namespace App\Monolog\Formatter;

use Monolog\Formatter\HtmlFormatter as BaseHtmlFormatter;

class HtmlFormatter extends BaseHtmlFormatter
{
    public function format(array $record)
    {
        $output = parent::format($record);

        if ($record['request']) {
            $output .= '<table cellspacing="1" width="100%">';
            foreach ($record['request'] as $key => $value) {
                $output .= $this->addRow($key, $this->convertToString($value));
            }
            $output .= '</table>';
        }

        if ($record['user']) {
            $output .= '<table cellspacing="1" width="100%">';
            $output .= $this->addRow('user', $this->convertToString($record['user']));
            $output .= '</table>';
        }

        return $output;
    }
}
