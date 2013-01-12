<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['opauth_config'] = array(
                                'path' => '/auth/login/',
                    			'callback_url' => '/auth/authenticate/',
                                'callback_transport' => 'post',
                                'security_salt' => 'f6as987LKDFH7f8as9123nfVB68a',
                                'debug' => false,
                                'Strategy' => array(                                    
                                    'Facebook' => array(
                                        'app_id' => '312001788889544',
                                        'app_secret' => 'a7e6f3cce24d20770cff66f05b07a064'
                                    )
                                )
                            );

/* End of file ci_opauth.php */
/* Location: ./application/config/ci_opauth.php */
