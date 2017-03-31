<?php

namespace CoreShop\Bundle\ResourceBundle\Routing;

use CoreShop\Component\Core\Metadata\MetadataInterface;
use CoreShop\Component\Core\Metadata\RegistryInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Yaml\Yaml;

final class ResourceLoader implements LoaderInterface
{
    /**
     * @var RegistryInterface
     */
    private $modelRegistry;

    /**
     * @var RouteFactoryInterface
     */
    private $routeFactory;

    /**
     * @param RegistryInterface $modelRegistry
     * @param RouteFactoryInterface $routeFactory
     */
    public function __construct(RegistryInterface $modelRegistry, RouteFactoryInterface $routeFactory)
    {
        $this->modelRegistry = $modelRegistry;
        $this->routeFactory = $routeFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $processor = new Processor();
        $configurationDefinition = new Configuration();

        $configuration = Yaml::parse($resource);
        $configuration = $processor->processConfiguration($configurationDefinition, ['routing' => $configuration]);

        $defaultRoutes = [
            'get' => ['GET'],
            'list' => ['GET'],
            'add' => ['POST'],
            'update' => ['POST'],
            'delete' => ['POST']
        ];
        $routesToGenerate = [];

        if (!empty($configuration['no_default_routes'])) {
            $defaultRoutes = [];
        }

        foreach ($defaultRoutes as $route => $methods) {
            $routesToGenerate[] = [
                'path' => $route,
                'action' => $route,
                'methods' => $methods
            ];
        }

        if (!empty($configuration['additional_routes'])) {
            $routesToGenerate = array_merge($routesToGenerate, $configuration['additional_routes']);
        }

        /** @var MetadataInterface $metadata */
        $metadata = $this->modelRegistry->get($configuration['alias']);
        $routes = $this->routeFactory->createRouteCollection();

        //$rootPath = sprintf('/%s/', isset($configuration['path']) ? $configuration['path'] : Urlizer::urlize($metadata->getPluralName()));
        //$identifier = sprintf('{%s}', $configuration['identifier']);

        $rootPath = '/admin/CoreShop/';
        $rootPath .= $metadata->getPluralName() . "/";

        foreach ($routesToGenerate as $route) {
            $indexRoute = $this->createRoute($metadata, $configuration, $rootPath . $route['path'], $route['action'], $route['methods']);
            $routes->add($this->getRouteName($metadata, $configuration, $route['action']), $indexRoute);
        }


        return $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'coreshop.resources' === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
        // Intentionally left blank.
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        // Intentionally left blank.
    }

    /**
     * @param MetadataInterface $metadata
     * @param array $configuration
     * @param string $path
     * @param string $actionName
     * @param array $methods
     *
     * @return Route
     */
    private function createRoute(MetadataInterface $metadata, array $configuration, $path, $actionName, array $methods)
    {
        $defaults = [
            '_controller' => $metadata->getServiceId('admin_controller').sprintf(':%sAction', $actionName),
        ];

        return $this->routeFactory->createRoute($path, $defaults, [], [], '', [], $methods);
    }

    /**
     * @param MetadataInterface $metadata
     * @param array $configuration
     * @param string $actionName
     *
     * @return string
     */
    private function getRouteName(MetadataInterface $metadata, array $configuration, $actionName)
    {
        $sectionPrefix = isset($configuration['section']) ? $configuration['section'].'_' : '';

        return sprintf('%s_%s%s_%s', $metadata->getApplicationName(), $sectionPrefix, $metadata->getName(), $actionName);
    }
}
