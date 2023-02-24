<?php

declare(strict_types=1);

if (!isset($argv[1])) {
    print_r(PHP_EOL . 'Аргумент не указан! Укажите ветку для поиска.' . PHP_EOL);
    exit(2);
}

$desired_branch = $argv[1];
$output = null;
$retval = null;

exec('git branch', $output, $retval);
foreach ($output as $key => $branch) {
    if (preg_match('~^\*\s' . $desired_branch . '~iu', $branch)) {
        echo "выбрана заблокированная ветка ($branch)" . PHP_EOL;
        exit(1);
    }
}
exit(0);
