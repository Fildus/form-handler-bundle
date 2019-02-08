The Symfony FormHandlerBundle
=============================

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
