<?php
/**
 * Copyright (c) Enalean, 2017. All Rights Reserved.
 *
 * This file is a part of Tuleap.
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tuleap\Configuration\Nginx;

class TuleapWeb
{
    const SSL_CERT_KEY_PATH  = '/etc/pki/tls/private/localhost.key.pem';
    const SSL_CERT_CERT_PATH = '/etc/pki/tls/certs/localhost.cert.pem';

    private $tuleap_base_dir;
    private $nginx_base_dir;
    private $server_name;

    public function __construct($tuleap_base_dir, $nginx_base_dir, $server_name)
    {
        $this->tuleap_base_dir = $tuleap_base_dir;
        $this->nginx_base_dir  = $nginx_base_dir;
        $this->server_name     = $server_name;

        $this->common = new Common($tuleap_base_dir, $nginx_base_dir, $server_name);
    }

    public function configure()
    {
        $this->common->deployConfigurationChunks();
        $this->common->deployMainNginxConf();

        $this->common->replacePlaceHolderInto(
            $this->tuleap_base_dir.'/src/etc/nginx18/tuleap.conf.dist',
            $this->nginx_base_dir.'/conf.d/tuleap.conf',
            array(
                '%ssl_certificate_key_path%',
                '%ssl_certificate_path%',
                '%sys_default_domain%',
            ),
            array(
                self::SSL_CERT_KEY_PATH,
                self::SSL_CERT_CERT_PATH,
                $this->server_name,
            )
        );

        $this->generateSSLCertificate();
    }

    private function generateSSLCertificate()
    {
        if (! file_exists(self::SSL_CERT_KEY_PATH)) {
            exec('openssl req -batch -nodes -x509 -newkey rsa:4096 -keyout '.self::SSL_CERT_KEY_PATH.' -out '.self::SSL_CERT_CERT_PATH.' -days 365 -subj "/C=XX/ST=SomeState/L=SomeCity/O=SomeOrganization/OU=SomeDepartment/CN='.$this->server_name.'" 2>/dev/null');
        }
    }
}
