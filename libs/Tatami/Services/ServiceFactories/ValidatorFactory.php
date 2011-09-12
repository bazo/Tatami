<?php
namespace Tatami\ServiceFactories;
use Nette\DI;
use Doctrine\Common\Annotations;
use Symfony\Component\Validator\Mapping\Cache;
use Symfony\Component\Validator\Mapping\BlackholeMetadataFactory;
use Symfony\Component\Validator\ConstraintValidatorFactory;
/**
 * Description of ValidatorFactory
 *
 * @author Martin
 */
class ValidatorFactory 
{
    public static function create(DI\Container $container)
    {
	$reader = new Annotations\AnnotationReader;
	$cache = new Cache\ApcCache('validator');
	$loader = new AnnotationLoader($reader);
	
	$metadataFactory = new \Doctrine\ORM\Mapping\ClassMetadataFactory($loader, $cache);
    }
}