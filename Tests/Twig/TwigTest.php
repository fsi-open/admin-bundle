<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\AcmeBundle\Tests\Twig;

use FSi\Bundle\AdminBundle\Twig\Extension\AdminExtension;

/**
 * @author Bartosz Bialek <bartosz.bialek@fsi.pl>
 */
class TwigTest extends \PHPUnit_Framework_TestCase
{
    protected $twig;

    public function setUp()
    {
        $loader = new \Twig_Loader_String();
        $twig = new \Twig_Environment($loader);

        $twig->addExtension(new AdminExtension('testBaseTemplate.html.twig'));

        $this->twig = $twig;
    }

    public function testGlobals()
    {
        $this->assertEquals('testBaseTemplate.html.twig', $this->twig->render('{{ base_template }}'));
    }
}
