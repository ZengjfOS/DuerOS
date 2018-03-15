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
 * @author liudesheng01@baidu.com
 **/
namespace Baidu\Apm\BotMonitorsdk;

class Certificate{
    /**
     * @param string $privateKeyContent 私钥内容,使用统计功能必须要提供
     * @return null
     */
    public function __construct($privateKeyContent = '') {
        $this->privateKey = $privateKeyContent;
    }

    /**
     * 生成签名
     * @param string $content 待签名内容
     * @return string|boolean 签名是否成功
     */
    public function getSig($content) {
        if(!$this->privateKey || !$content) {
            return false;
        }
        
        $privateKey = openssl_pkey_get_private($this->privateKey, '');
        
        if ($privateKey) {
            $encryptedData = '';
            // 私钥加密
            openssl_sign($content, $encryptedData, $privateKey, OPENSSL_ALGO_SHA1);
            return base64_encode($encryptedData);
        }
        return false;
    }
}