The Symfony FormHandlerBundle
=============================

[![Build Status](https://travis-ci.org/TBoileau/form-handler-bundle.svg?branch=master)](https://travis-ci.org/TBoileau/form-handler-bundle)

[![SymfonyInsight](https://insight.symfony.com/projects/6fe933a5-3c2d-4688-863d-14f61556359d/big.svg)](https://insight.symfony.com/projects/6fe933a5-3c2d-4688-863d-14f61556359d)

The FormHandlerBundle is (IMO) a good way to handling your forms, in compliance with SOLID principles, in particular the **S**ingle responsability principle.

In the official documentation, you are told that your forms must be handled in a controller, like this :
```php
<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    public function new(Request $request)
    {
        // just setup a fresh $task object (remove the dummy data)
        $task = new Task();
    
        $form = $this->createForm(TaskType::class, $task);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();
    
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();
    
            return $this->redirectToRoute('task_success');
        }
    
        return $this->render('task/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
```

What **Single responsability principle** told us ? 
Thanks to Wikipedia : *a class should have only a single responsibility (i.e. only changes to one part of the software's specification should be able to affect the specification of the class).*

So we notice that our controller doesn't respect this principle because we handle the creation and the submission of our form, more important, we have some business logic. 
But, a controller has only one responsability : **get a request and send a response**, all the logic between these actions must be manage in service, in a handler in this case.

Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require tboileau/form-handler-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require tboileau/form-handler-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new TBoileau\Bundle\FormHandlerBundle\TBoileauFormHandlerBundle()
        ];

        // ...
    }

    // ...
}
```

Configuration
-------------

First, you need to define your handler in `services.yaml` with this tag `t_boileau.form_handler` :
```yaml
services:
    # ...
    App\Handler\FooHandler:
        tags:
            - { name: t_boileau.form_handler }
```

In case you have multiple handlers, you can define in one time all your handlers :
```yaml
services:
    # ...
    App\Handler\:
        resource: '../src/Handler'
        tags:
            - { name: t_boileau.form_handler }
```

Create your first handler
-------------------------

A handler is necessarily attach to a form, then you can add your business logic :
```php
<?php
namespace App\Handler;

use App\Form\FooType;
use TBoileau\Bundle\FormHandlerBundle\Handler\HandlerInterface;
use TBoileau\Bundle\FormHandlerBundle\Config\HandlerConfigInterface;
use TBoileau\Bundle\FormHandlerBundle\Manager\HandlerManagerInterface;

class FooHandler implements HandlerInterface
{
    /**
     * Add your logic when the form is submitted and valid.
     *
     * @param HandlerManagerInterface $manager
     */
    public function process(HandlerManagerInterface $manager): void
    {
        // PUT SOME LOGIC HERE
        
        // You can throw an error that will be added to your form, to be usable in your view.
        $manager->addError("An error occured");
    }

    /**
     * Configure your handler
     *
     * @param HandlerConfigInterface $config
     */
    public function configure(HandlerConfigInterface $config): void
    {
        $config->use(FooType::class);
    }
}
```

As you can see, in the `configure` method we attach the form name to our handler with `use` method of `$config`.

Then, you can put your logic when your form is submitted and valid.

Inject service in your form handler
-----------------------------------

You don't need to define your dependencies in `services.yaml`. Since 3.4, you can use autowiring and type-hint to inject automaticaly in your service :
```php
<?php
// ...
use Doctrine\ORM\EntityManagerInterface;

class FooHandler implements HandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * FooHandler constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // ...
}
```

Use your handler in a controller
--------------------------------

To use your form handler, you need to inject in your controller the `TBoileau\Bundle\FormHandlerBundle\Factory\ManagerInterface`, and create a `HandlerManager` with your form handler in argument :
```php
<?php
// src/Controller/DefaultController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use TBoileau\Bundle\FormHandlerBundle\Factory\ManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Handler\FooHandler;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(ManagerFactoryInterface $managerFactory, Request $request)
    {
        $foo = new Foo();
        // We create a new HandlerManager with our FooHandler and a new Foo instance.
        // Handle the request
        $handlerManager = $managerFactory->create(FooHandler::class, $foo)->handle($request);
        
        // If the manager has tested the validity of the form and processed your logic
        if($handlerManager->isValid()) {
            // Return a response like a redirection
            return $this->redirectToRoute("foo_index");
        }
        
        return $this->render('default/index.html.twig', [
            // createView is just a shortcut of form's createView method
            'form' => $handlerManager->createView(),
        ]);
    }
}
```

Add a data mapper for your DTO
------------------------------

In some cases, we do not want to use an entity in a form, but rather prefer to use a DTO (Data Transfert Object). This bundle helps you to manage this issue more simply.

First, we implement our entity :
```php
<?php
// src/Controller/DefaultController.php

namespace App\Entity;

class Foo
{
    /**
     * @var string
     */
    private $bar = "";

    /**
     * @return string
     */
    public function getBar(): string
    {
        return $this->bar;
    }

    /**
     * @param string $bar
     */
    public function setBar(string $bar): void
    {
        $this->bar = $bar;
    }
}
```

Then, the DTO :
```php
<?php
// src/Controller/DefaultController.php

namespace App\Model;

class Bar
{
    /**
     * @var string
     */
    private $name = "";

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
```

Let's now go to the form :
```php
<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Model\Bar;

/**
 * Class FooType
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Tests\Form
 * @author Thomas Boileau <t-boileau@email.com>
 */
class FooType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name");
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("data_class", Bar::class);
    }
}
```

Finally, all that remains is to create our `DataMapper` and configure our `Handler` :
```php
<?php

namespace App\DataMapper;

use TBoileau\Bundle\FormHandlerBundle\DataMapper\DataMapperInterface;
use TBoileau\Bundle\FormHandlerBundle\Exception\MappingFailedException;
use TBoileau\Bundle\FormHandlerBundle\Tests\Model\Bar;
use TBoileau\Bundle\FormHandlerBundle\Tests\Model\Foo;

class FooMapper implements DataMapperInterface
{
    /**
     * @param Foo $data
     * @return Bar
     */
    public function map($data)
    {
        $bar = new Bar();
        $bar->setName($data->getBar());

        return $bar;
    }

    /**
     * @param Bar $modelData
     * @param Foo $handleData
     * @return Foo
     */
    public function reverseMap($modelData, $handleData)
    {
        if ($modelData->getName() === "fail") {
            throw new MappingFailedException("Bar can't be equal to 'fail'.");
        }

        $handleData->setBar($modelData->getName());

        return $handleData;
    }
}
```

*Note: Don't forget to return the data in `reverseMap` method.*

```php
<?php

namespace App\Handler;

use TBoileau\Bundle\FormHandlerBundle\Config\HandlerConfigInterface;
use TBoileau\Bundle\FormHandlerBundle\Handler\HandlerInterface;
use TBoileau\Bundle\FormHandlerBundle\Manager\HandlerManagerInterface;
use App\DataMapper\FooMapper;
use App\Form\FooType;

class FooHandler implements HandlerInterface
{
    /**
     * @inheritdoc
     */
    public function process(HandlerManagerInterface $manager): void
    {

    }

    /**
     * @inheritdoc
     */
    public function configure(HandlerConfigInterface $config): void
    {
        $config
            ->use(FooType::class)
            ->mappedBy(FooMapper::class)
        ;
    }
}

```

**You need to tag your `FooMapper`**

First, you need to define your data mapper in `services.yaml` with this tag `t_boileau.data_mapper` :
```yaml
services:
    # ...
    App\DataMapper\FooMapper:
        tags:
            - { name: t_boileau.data_mapper }
```

In case you have multiple data mappers, you can define in one time all your data mappers :
```yaml
services:
    # ...
    App\DataMapper\:
        resource: '../src/DataMapper'
        tags:
            - { name: t_boileau.data_mapper }
```