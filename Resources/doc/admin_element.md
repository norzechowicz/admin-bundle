# Admin elements

Admin panel is build from admin elements. Admin element is nothing more that object that allows you to modify
your website resources.

Admin elements have different types, at the moment you can choose from

* [Doctrine CRUD (Create Read Update Delete)](admin_element_crud.md)
* [Doctrine List](admin_element_list.md)
* [Doctrine Form](admin_element_form.md)
* [Doctrine Batch/Delete](admin_element_batch.md)
* [Doctrine Resource](admin_element_resource.md)
* [Doctrine Display](admin_element_display.md)

If default element types are not enough you can use [event system](events.md) to modify admin elements
behavior.

# Admin elements registration

Each admin element must be registered in admin elements manager. In other way admin bundle will not be
able to handle it properly. There are few ways to do it:

### Annotation

Probably the simplest way

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/User

namespace FSi\Bundle\DemoBundle\Admin;

use use FSi\Bundle\AdminBundle\Annotation as Admin;

/**
 * @Admin\Element
 */
class UserElement extends CRUDElement
{
}
```

### Service

You can register your admin element as a symfony2 tagged service

XML Example:
```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
<services>

    <service id="fsi_demo_bundle.admin.element.user" class="FSi\Bundle\DemoBundle\Admin\UserElement">
        <tag name="admin.element"/>
    </service>

</services>
</container>
```

YML Example:
```yaml
services:
    tc_api.admin.element.admin_user:
        class: FSi\Bundle\DemoBundle\Admin\UserElement
        tags:
            - { name: admin.element }
```

# Injecting request stack to admin element

```php
namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\RequestStackAware;

/**
 * @Admin\Element
 */
class UserElement extends CRUDElement implements RequestStackAware
{
    private $requestStack;

    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
}
```

This technique is particular useful when you register admin elements by annotations.

[Back to index](index.md)
