# Dynamic Settings

[![Latest Stable Version](https://poser.pugx.org/Fantismic/dynamic-settings/v)](https://packagist.org/packages/Fantismic/dynamic-settings) 
[![Total Downloads](https://poser.pugx.org/Fantismic/dynamic-settings/downloads)](https://packagist.org/packages/Fantismic/dynamic-settings) 
[![Latest Unstable Version](https://poser.pugx.org/Fantismic/dynamic-settings/v/unstable)](https://packagist.org/packages/Fantismic/dynamic-settings) 
[![PHP Version Require](https://poser.pugx.org/Fantismic/dynamic-settings/require/php)](https://packagist.org/packages/Fantismic/dynamic-settings)


Dynamic Settings is a Laravel / Livewire package that allows you to manage custom settings of your application dynamically.

Let's say you have several notifications to different mailing lists, but you would like to be able to change whether they are sent or not, or which email they are sent to without having to modify the code or the env file.

This is where Dynamic Settings comes in.

You can make any number of configurations, grouped and associated with each other, and then use their values ​​in the code. This way when sending an email, for example, $to will be DynSettings::get('admin.mail') and not "admin@mysite.com".

## Requirements

- Laravel 10/11

### Optionals requirements
- [Livewire 3](https://livewire.laravel.com/) (*)
- [Wire UI 2](https://wireui.dev/) (**)


(*) Livewire 3 is required only for the component to manage the settings. You can always ignore that and make your own.

(**) As said before, WireUI is a great package for those components, we included a normal view and a WireUI view, they set up automatically base on your app.

## Installation

```
composer require fantismic/dynamic-settings
```

After you install you need to publish the migration file...

```
php artisan vendor:publish --provider="Fantismic\\DynSettings\\Providers\\DynSettingsProvider" --tag="migrations"
```

...and run the migration.


```
php artisan migrate
```

That's it. You are ready to go!


### Optionals

You can publish the configuration file to customize behavior, such as full page component mode or preferred blade.

```
php artisan vendor:publish --provider="Fantismic\\DynSettings\\Providers\\DynSettingsProvider" --tag="config"
```


## Using the built in component

### Component mode

We provide a livewire component for manage the settings. You just need to include it like any other componenet wherever you want it.

```
<livewire:DynamicSettings /> 
```

### Fullpage mode

You can set the component to render in fullpage in the config file. If you do, you should add the route in your routes/web.php

```
<?php
use Fantismic\DynSettings\Livewire\DynamicSettingsComponent;

Route::get('/fullpageSettings',DynamicSettingsComponent::class);
```

## Facade

We provide one or two methods in the facade.

```
use Fantismic\DynSettings\Facades\DynSettings;
```

Lets say you have these keys:

```
notifications.send
notifications.alwayscc.send
notificactions.alwayscc.emails
```

### Getting data

**DynSettings::all()**: (array)

```
> DynSettings::all()
= [
    [
      "id" => 1,
      "key" => "notifications.send",
      "value" => "true",
      "type" => "bool",
      "name" => "Send email notifications",
      "description" => "Check this item in order to active all notifications.",
      "group" => "Notifications",
      "associate_with" => "General",
    ],
    [
      "id" => 2,
      "key" => "notifications.alwayscc.send",
      "value" => "true",
      "type" => "bool",
      "name" => "Send always CC",
      "description" => "Add to all emails a carbon copy",
      "group" => "Notifications",
      "associate_with" => "Always CC",
    ],
    [
      "id" => 3,
      "key" => "notifications.alwayscc.emails",
      "value" => "["test@mail.com","admin@app.com"]",
      "type" => "array",
      "name" => "CC email list",
      "description" => "List of emails that should recibe a copy for every send mail",
      "group" => "Notifications",
      "associate_with" => "Always CC",
    ],
  ]

>

```

**DynSettings::getArray()**: (array)
```
> DynSettings::getArray()
= [
    "notifications" => [
      "send" => true,
      "alwayscc" => [
        "send" => true,
        "emails" => [
          "test@mail.com",
          "admin@app.com",
        ],
      ],
    ],
  ]

```

**DynSettings::getObject()**: (object)
```
> DynSettings::getObject()
= {#1
    +"notifications": {#2
      +"send": true,
      +"alwayscc": {#3
        +"send": true,
        +"emails": [
          "test@mail.com",
          "admin@app.com",
        ],
      },
    },
  }

```

**DynSettings::getDot()**: (array)
```
> DynSettings::getDot()
= [
    "notifications.send" => true,
    "notifications.alwayscc.send" => true,
    "notifications.alwayscc.emails" => [
      "test@mail.com",
      "admin@app.com",
    ],
  ]

```

**DynSettings::get(** *(string)* **$key)**: (mixed)
```
> DynSettings::get('notifications.alwayscc.send')
= true

------------------------------------------------------------

> DynSettings::get('notifications.alwayscc.emails')
= [
    "test@mail.com",
    "admin@app.com",
  ]

```

**DynSettings::getKeyData(** *(string)* **$key)**: (array)
```
> DynSettings::getKeyData('notifications.alwayscc.send')
= {#5459
    +"id": 2,
    +"key": "notifications.alwayscc.send",
    +"value": "true",
    +"type": "bool",
    +"name": "Send always CC",
    +"description": "Add to all emails a carbon copy",
    +"group": "Notifications",
    +"associate_with": "Always CC",
  }

```

**DynSettings::getModel(** *(string)* **$key)**: (Eloquent Model)
```
> DynSettings::getModel('notifications.alwayscc.send')
= Fantismic\DynSettings\Models\DynamicSettings {#
    id: 2,
    key: "notifications.alwayscc.send",
    value: "true",
    type: "bool",
    name: "Send always CC",
    description: "Add to all emails a carbon copy",
    group: "Notifications",
    associate_with: "Always CC",
  }

>

```

**DynSettings::getGroups()**: (array)
```
> DynSettings::getGroups()
= [
    "Notifications",
  ]

```

**DynSettings::getAssocs(** *(string)* **$group)**: (array)
```
> DynSettings::getAssocs("Notifications")
= [
    "General",
    "Always CC",
  ]

```

**DynSettings::getByGroup(** *(string)* **$group)**: (array)
```
> DynSettings::getByGroup("Notifications")
= [
    [
      "id" => 1,
      "key" => "notifications.send",
      "value" => "true",
      "type" => "bool",
      "name" => "Send email notifications",
      "description" => "Check this item in order to active all notifications.",
      "group" => "Notifications",
      "associate_with" => "General",
    ],
    [
      "id" => 2,
      "key" => "notifications.alwayscc.send",
      "value" => "false",
      "type" => "bool",
      "name" => "Send always CC",
      "description" => "Add to all emails a carbon copy",
      "group" => "Notifications",
      "associate_with" => "Always CC",
    ],
    [
      "id" => 3,
      "key" => "notifications.alwayscc.emails",
      "value" => "["test@mail.com","admin@app.com"]",
      "type" => "array",
      "name" => "CC email list",
      "description" => "List of emails that should recibe a copy for every send mail",
      "group" => "Notifications",
      "associate_with" => "Always CC",
    ],
  ]

------------------------------------------------------------
```

### Setting / Updating data


**DynSettings::set(** *(string)* **$group**, *(mixed)* **$value)**: (bool)

```
> DynSettings::set('notifications.alwayscc.send',false)
= true
```

**DynSettings::updateGroupName(** *(string)* **$oldName**, *(string)* **$newName)**: (bool)
```
> DynSettings::updateGroupName("Notifications", "Mailing")
= true

```

**DynSettings::updateName(** *(string)* **$key**, *(string)* **$newName)**: (bool)
```
> DynSettings::updateName('notifications.alwayscc.send','Always send carbon copy')
= true

```

**DynSettings::updateDescription(** *(string)* **$key**, *(string)* **$newDescription)**: (bool)
```
> DynSettings::updateDescription('notifications.alwayscc.send','Set CC on every outgoing mail')
= true

```

**DynSettings::updateAssoc(** *(string)* **$key**, *(string)* **$newAssoc)**: (bool)
```
> DynSettings::updateAssoc('notifications.alwayscc.send','CC')
= true

```

**DynSettings::delete(** *(int)* **$id**)**: (bool)
```
> DynSettings::delete(3)
= true
```

**DynSettings::deleteByKey(** *(string)* **$key**)**: (bool)
```
> DynSettings::deleteByKey('notifications.alwayscc.send')
= true

```

### Using the settings


**DynSettings::is(** *(string)* **$key**, *(mixed)* **$value)**: (bool)
```
> DynSettings::is('notifications.alwayscc.send', false)
= true
```

**DynSettings::should(** *(string)* **$key)**: (bool)
```
> DynSettings::should('notifications.alwayscc.send')
= false
```