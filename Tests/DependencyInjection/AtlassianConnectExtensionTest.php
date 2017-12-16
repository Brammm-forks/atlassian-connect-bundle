<?php declare(strict_types = 1);

namespace Bukashk0zzz\FilterBundle\Tests\DependencyInjection;

use AtlassianConnectBundle\DependencyInjection\AtlassianConnectExtension;
use Doctrine\Common\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * AtlassianConnectExtensionTest
 */
class AtlassianConnectExtensionTest extends TestCase
{
    /**
     * @var AtlassianConnectExtension
     */
    private $extension;

    /**
     * @var ContainerBuilder Container builder
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->extension = new AtlassianConnectExtension();
        $this->container = new ContainerBuilder();
        $this->container->registerExtension($this->extension);

    }

    /**
     * Test load extension
     */
    public function testLoadExtension(): void
    {
        $this->container->set(RouterInterface::class, new \stdClass());
        $this->container->set(KernelInterface::class, new \stdClass());
        $this->container->set(TokenStorage::class, new \stdClass());
        $this->container->set(ManagerRegistry::class, new \stdClass());

        $this->container->prependExtensionConfig($this->extension->getAlias(), [
            'token_lifetime' => 86400,
            'dev_tenant' => 1,
            'prod' => [],
            'dev' => [],
        ]);
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();

        // Check that services have been loaded
        static::assertTrue($this->container->has('jwt_user_provider'));
        static::assertTrue($this->container->has('jwt_authenticator'));
    }
}
