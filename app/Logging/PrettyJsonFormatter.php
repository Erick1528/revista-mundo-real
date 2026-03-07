<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;

/**
 * Formatter que escribe contexto y extra como JSON indentado para leer mejor en los logs.
 */
class PrettyJsonFormatter extends LineFormatter
{
    public function __construct(?string $format = null, ?string $dateFormat = null, bool $allowInlineLineBreaks = true, bool $ignoreEmptyContextAndExtra = false, bool $includeStacktraces = false)
    {
        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra, $includeStacktraces);
        $this->setJsonPrettyPrint(true);
        $this->addJsonEncodeOption(JSON_UNESCAPED_UNICODE);
    }
}
