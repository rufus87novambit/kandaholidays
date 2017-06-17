<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

/**
 * IOL configuration
 *
 * Class IOL_Config
 */
class IOL_Config {

    /**
     * Provider ID
     * @var string
     */
    private static $id = 'iol';

    /**
     * Provider name
     * @var string
     */
    private static $name = 'IOL';

    /**
     * Provider mode test | live
     * @var string
     */

    private static $mode = 'live';

    /**
     * Access details
     *
     * @var array
     */
    private static $access = array(
        'test' => array(
            'code'      => 'IOLWSDEV',
            'password'  => 'webservices',
            'token'     => 'TRA_STG!03042015_125718',
            'url'       => 'http://www.v3.staging.illusionswebservices.iwtx.com/illusionsHotelSearch.ashx',
        ),
        'live' => array(
            'code'      => 'TRA_KANDA_PROD',
            'password'  => 'TRA_KANDA_XML',
            'token'     => 'KCL960313',
            'url'       => 'http://www.v3.illusionswebservices.iwtx.com/illusionsHotelSearch.ashx'
        )
    );

    /**
     * Date format
     *
     * @var string
     */
    private static $date_format = 'Ymd';

    /**
     * Request caching lifetime in seconds
     *
     * @var array
     */
    private static $cache_timeout = array(
        'search'        => 1 * HOUR_IN_SECONDS
    );

    /**
     * MySQL LIMIT
     * @var array
     */
    private static $sql_limit = array(
        'search' => 5
    );

    /**
     * Cities code/name association
     * @var array
     */
    private static $cities = array(
        'AUH'   => 'Abu Dhabi',
        'AJMA'  => 'Ajman',
        'DXB'   => 'Dubai',
        'FUJA'  => 'Fujairah',
        'RASK'  => 'Ras al Khaimah',
        'SHAR'  => 'Sharjah'
    );

    /**
     * Room booking restrictions possible types
     * @var array
     */
    private static $possible_restrictions = array(
        'minnights',
        'maxnights',
        'nocheckin',
        'nocheckout'
    );

    /**
     * Hotels blacklist
     *
     * @var array
     */
    private static $hotels_blacklist = array(
        'AUH'   => array(
            '32-5249',
            '32-4741',
            '32-5075',
            '32-5071',
            '32-4868'
        ),
        'AJMA'  => array(
            '32-5177',
            '32-4588',
            '32-4749',
            '32-5253',
            '32-4987',
            '32-5013'
        ),
        'DXB'   => array(
            '32-5215',
            '32-5100',
            '32-5135',
            '32-4805',
            '32-5174',
            '32-5057',
            '32-4857',
            '32-4964',
            '32-4565',
            '32-4877',
            '32-5101',
            '32-4740',
            '32-4568',
            '32-4904',
            '32-4906',
            '32-5183',
            '32-5189',
            '32-4571',
            '32-5032',
            '32-5271',
            '32-4997',
            '32-5246',
            '32-4726',
            '32-4939',
            '32-5002',
            '32-4938',
            '32-4581',
            '32-5432',
            '32-4913',
            '32-5157',
            '32-4587',
            '32-4834',
            '32-4911',
            '32-4822',
            '32-5208',
            '32-5149',
            '32-4773',
            '32-5243',
            '32-4598',
            '32-5392',
            '32-5022',
            '32-4829',
            '32-4852',
            '32-4851',
            '32-4759',
            '32-4824',
            '32-4835',
            '32-4893',
            '32-4603',
            '32-5393',
            '32-5308',
            '32-5219',
            '32-4804',
            '32-4605',
            '32-4841',
            '32-5226',
            '32-5094',
            '32-5056',
            '32-4819',
            '32-5254',
            '32-4639',
            '32-5087',
            '32-5089',
            '32-5111',
            '32-5302',
            '32-4881',
            '32-4747',
            '32-4611',
            '32-5459',
            '32-4870',
            '32-5451',
            '32-4600',
            '32-4940',
            '32-5169',
            '32-4820',
            '32-4803',
            '32-4628',
            '32-5230',
            '32-4633',
            '32-5151',
            '32-4634',
            '32-5457',
            '32-4918',
            '32-4642',
            '32-5212',
            '32-5236',
            '32-5039',
            '32-4916',
            '32-5112',
            '32-5229',
            '32-5414',
            '32-4862',
            '32-4864',
            '32-4863',
            '32-4866',
            '32-4662',
            '32-4772',
            '32-5133',
            '32-4663',
            '32-4665',
            '32-4832',
            '32-4873',
            '32-4874',
            '32-5279',
            '32-4976',
            '32-5277',
            '32-5159',
            '32-5394',
            '32-5450',
            '32-4678',
            '32-4681',
            '32-5452',
            '32-4816',
            '32-4682',
            '32-4972',
            '32-4796',
            '32-5185',
            '32-4719',
            '32-4683',
            '32-4684',
            '32-4844',
            '32-4650',
            '32-4686',
            '32-4690',
            '32-5061',
            '32-4908',
            '32-4821',
            '32-4806',
            '32-4818',
            '32-4693',
            '32-5460',
            '32-5134',
            '32-4764',
            '32-4840',
            '32-4794',
            '32-5258',
            '32-5257',
            '32-5259',
            '32-5256',
            '32-4909',
            '32-4695',
            '32-4833',
            '32-4849',
            '32-4880',
            '32-4993',
            '32-4728',
            '32-4754',
            '32-4830',
            '32-4807',
            '32-4800',
            '32-4917',
            '32-4647',
            '32-5454',
            '32-5472',
            '32-4802',
            '32-5214',
            '32-5099',
            '32-4714',
            '32-4817',
            '32-5496',
            '32-5456',
            '32-5411',
            '32-5284',
            '32-5043',
            '32-5313',
            '32-5314',
            '32-5491',
            '32-4847',
            '32-5179',
			'32-5207'
        ),
        'FUJA'  => array(
            '32-5294',
            '32-4694'
        ),
        'RASK'  => array(
            '32-5181',
            '32-4641',
            '32-5080',
            '32-4884'
        ),
        'SHAR'  => array(
            '32-5216',
            '32-5220',
            '32-5221',
            '32-5504',
            '32-4733',
            '32-5402',
            '32-4846',
            '32-4860',
            '32-5152',
            '32-4843',
            '32-4812',
            '32-5389',
            '32-4765',
            '32-5223',
            '32-5498',
            '32-4790',
            '32-4813',
            '32-4920',
            '32-4705',
            '32-5222',
            '32-5167',
            '32-4848'
        )
    );

    /**
     * Request args
     * @var array
     */
    private static $request_args = array(
        'timeout' => 300,
        'sslverify' => false,
        'headers' => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Accept:text/xml'
        ),
        'body' => array(),
    );

    /**
     * Get configuration value
     *
     * @param string $property
     * @param string $delimiter
     * @return null
     */
    public static function get( $property = '', $delimiter = '->' ) {
        try {
            $value = self::_get( $property, $delimiter );
        } catch( Exception $e ) {
            echo $e->getMessage(); die;
        }

        return $value;
    }

    /**
     * Get configuration value
     *
     * @param string $property
     * @param string $delimiter
     * @return null
     * @throws Exception
     */
    private static function _get( $property = '', $delimiter = '->' ) {
        if( ! $property ) {
            return null;
        }

        $property = explode( $delimiter, $property );
        $key = array_shift( $property );

        $value = self::${$key};
        foreach( $property as $p ) {
            if( ! array_key_exists( $p, $value ) ) {
                throw new Exception( sprintf( "Cannot find property \"%s\"", $p ) );
            }
            $value = $value[ $p ];
        }

        return $value;
    }

}