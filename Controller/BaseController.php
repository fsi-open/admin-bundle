<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class BaseController extends Controller
{
    /**
     * @return \FSi\Bundle\AdminBundle\Structure\GroupManager
     */
    public function getGroupManager()
    {
        return $this->get('admin.group.manager');
    }
}