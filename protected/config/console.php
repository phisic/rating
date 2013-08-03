<?php

return CMap::mergeArray(require(dirname(__FILE__) . '/base.php'), array(
            'name' => 'Console Application',
            'import' => array(
                'application.modules.user.*',
            ),
            'components' => array(
                'db' => array(
                    'password' => '',
                ),
                'bb' => array(
                    'class' => 'ext.BBOpen',
                    'apiKey' => 'wsxb4j7r9xzs6f8skwhk8d56',
                    'categories'=> array(
                       // 'abcat0100000',
                       // 'abcat0200000',
                       // 'abcat0800000',
                        'abcat0400000',
                        'abcat0500000',
                        'abcat0300000',
                        'pcmcat245100050028',
                        'pcmcat242800050021',
                        'abcat0600000',
                        'abcat0700000',
                        'pcmcat252700050006',
                        'pcmcat248700050021',
                        'abcat0900000',
                    )
                ),
                'log' => array(
                    'class' => 'CLogRouter',
                    'routes' => array(
                        array(
                            'class' => 'CEmailLogRoute',
                            'levels' => 'error, warning',
                            'emails' => 'murod9@gmail.com',
                        ),
                    ),
                ),
            ),
        ));
