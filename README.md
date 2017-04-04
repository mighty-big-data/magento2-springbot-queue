[![GPL licensed](https://img.shields.io/badge/license-GPL-blue.svg)](https://raw.githubusercontent.com/springbot/magento2-queue/master/LICENSE.md)

## Magento2 Job Queue

This module provides functionality to schedule and run jobs in a queue for Magento2. The basic idea is to allow 
developers to defer tasks that would otherwise delay the page load time and cause site slowness. Example use cases may 
be sending an email, communicating with a remote API, or running cleanup tasks in the background.

While originally written for our Springbot Magento2 integration, this module can be utilized for many other use cases.

The module provides both programmatic and command line ways to enqueue, run, and view jobs. The key components of a job 
are a fully qualified class name, a method name, and a list of arguments. There are a few requirements: the class must 
be autoloadable, the method must be public, and the params must not contain objects. Additional options are priority, 
queue name, and "run at" time.

### Installation

Composer is the preferred method of installation:
```bash
composer require springbot/magento2-queue
php bin/magento module:enable Springbot_Queue
```

### Command line usage

Enqueue a job
```bash
 php bin/magento springbot:queue:enqueue <class> <method> [<queue>] [<priority>] [<params>1] ... [<params>N]
```

List current jobs in the queue
```bash
 php bin/magento springbot:queue:list
```

Process the queue 
```bash
php bin/magento springbot:queue:process
```

### Programmatic usage

```php
<?php

namespace Your\Name\Space;

use Springbot\Queue\Model\Queue;

class QueueExample
{
    private $queue;

    /**
     * QueueExample constructor
     * @param Queue $queue
     */
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * Queue examples
     */
    public function runQueueExamples()
    {
        // Enqueue a job 
        $this->queue->scheduleJob('\Fully\Qualified\ClassName', 'methodToRun', ['arg1', 'arg2']);
        
        // Process the queue 
        $this->queue->runNextJob();
        
        // Get a collection of current jobs
        $jobs = $this->queue->getCollection();
    }
}
```

### API Usage

To process the queue make a GET request to the following API endpoint:
```
/V1/springbot/queue/process
```

To view current jobs make a GET request to the following API endpoint:
```
/V1/springbot/queue/jobs
```

For security purposes jobs cannot be enqueued directly from the web API.