<?php

#
#    S P Y C
#      a simple php yaml class
#   v0.2(.5)
#
# author: [chris wanstrath, chris@ozmm.org]
# websites: [http://www.yaml.org, http://spyc.sourceforge.net/]
# license: [MIT License, http://www.opensource.org/licenses/mit-license.php]
# copyright: (c) 2005-2006 Chris Wanstrath
#

include('../spyc.php');

$array = Spyc::YAMLLoad('../spyc.yaml');

echo '<pre><a href="spyc.yaml">spyc.yaml</a> loaded into PHP:<br/>';
print_r($array);
echo '</pre>';


echo '<pre>YAML Data dumped back:<br/>';
echo Spyc::YAMLDump($array);
echo '</pre>';
