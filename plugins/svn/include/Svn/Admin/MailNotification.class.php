<?php
/**
 * Copyright (c) Enalean, 2016 - 2017. All rights reserved
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/
 */

namespace Tuleap\Svn\Admin;

use PFUser;
use Tuleap\Svn\Repository\Repository;
use User_UGroup;

class MailNotification
{
    /**
     * @var array
     */
    private $notified_mails;
    private $path;
    private $repository;
    private $id;
    /**
     * @var PFUser[]
     */
    private $notified_users;
    /**
     * @var User_UGroup[]
     */
    private $notified_ugroups;

    public function __construct(
        $id,
        Repository $repository,
        $path,
        array $notified_mails,
        array $notified_users,
        array $notified_ugroups
    ) {
        $this->id               = $id;
        $this->repository       = $repository;
        $this->notified_mails   = $notified_mails;
        $this->path             = $path;
        $this->notified_users   = $notified_users;
        $this->notified_ugroups = $notified_ugroups;
    }

    /**
     * @return array
     */
    public function getNotifiedMails()
    {
        return $this->notified_mails;
    }

    public function getNotifiedMailsAsString()
    {
        return implode(',', $this->notified_mails);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PFUser[]
     */
    public function getNotifiedUsers()
    {
        return $this->notified_users;
    }

    /**
     * @return User_UGroup[]
     */
    public function getNotifiedUgroups()
    {
        return $this->notified_ugroups;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
