<?php

return CMap::mergeArray(require(dirname(__FILE__) . '/main.sample.php'), array(
            'components' => array(
                'db' => array(
                    'password' => '',
                ),
                'log' => array(
                    'routes' => array(
                        array(
                            'class' => 'CProfileLogRoute',
                            'report' => 'summary',
                        ),
                        array(
                            'class' => 'CWebLogRoute',
                        ),
                    ),
                ), /**/
            )
        ));