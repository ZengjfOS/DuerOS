<?php
 /**
 * Copyright (c) 2017 Baidu, Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @desc Certificate类的测试类
 */

 require dirname(__FILE__) . "/../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Baidu\Apm\BotMonitorsdk\Certificate;

class CertificateTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
    	$privateKey = file_get_contents(dirname(__FILE__)."/../../rsa_private_key.pem");
    	$this->certificate = new Certificate($privateKey);
    }	

	/**
     * @desc 测试签名方法
     */
	function testGetSig(){
		$str = "testcontent";
		$sigret = "oVS9T3ekkmsu8Q0Cl4josTcXgeF4d4+ycv0kRP7AmI89xXSKsoiF3R//lv5uoiFd9MzX9UeOEmEf5BpFyPGOtkC3mgc5Mv9CloWkUVQCbHr1rU0PGCCdessh6tEvTjY5NXpj03/dxK+4gifuV3pe60Hdx1HNqNbouVH6nG43L9o=";
		$ret = $this->certificate->getSig($str);
		$this->assertEquals($ret,$sigret);
	}
}
