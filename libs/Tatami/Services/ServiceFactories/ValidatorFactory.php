<?php
namespace Tatami\ServiceFactories;
use Nette\DI\IContainer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ValidatorContext;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Validator\ValidatorFactory as SymfonyValidatorFactory;
/**
 * Description of ValidatorFactory
 *
 * @author Martin
 */
class ValidatorFactory 
{
    public static function create(IContainer $context)
    {
	$loaders = array();
        $context = new ValidatorContext();
	
	AnnotationRegistry::registerAutoloadNamespaces(array(
	    '\Doctrine\ORM\Mapping\\',
	    '\Symfony\Component\Validator\Constraints\\',
	    
	    
	    ));
	
	$annotationReader = new AnnotationReader();
	$annotationReader->setIgnoreNotImportedAnnotations(true);
	$loader = new AnnotationLoader($annotationReader);
	
	$context->setClassMetadataFactory(new ClassMetadataFactory($loader));
        $context->setConstraintValidatorFactory(new ConstraintValidatorFactory());

	$factory = new SymfonyValidatorFactory($context);

	return $factory->getValidator();
    }
}