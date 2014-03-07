<?php

$seq = array_merge(range(0,9), range('A', 'Z'), range('a', 'z'), ['_', '.', '~', '-']);
shuffle($seq);

$install = "<?php\nreturn " . var_export(array('digits' => join('', $seq), 'array' => array_combine($seq, range(1, count($seq)))), true) . ";\n";
file_put_contents(__DIR__ . '/config.php', $install);
