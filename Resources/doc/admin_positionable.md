# How to manually position entities on lists

This feature provides way to change position of item on the list gradually.
This mean that position will be changed by 1.

This feature works with conjunction with [Sortable behavior extension for Doctrine2](https://github.com/doctrine-extensions/DoctrineExtensions/blob/master/doc/sortable.md)

## Usage

Sample entity:

**Note:** **@Gedmo\Mapping\Annotation\SortablePosition** points column to store **position** index

```php
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FSi\Bundle\AdminPositionableBundle\Model\PositionableInterface;

class Promotion implements PositionableInterface
{
    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     */
    protected $position;

    /**
     * {@inheritdoc}
     */
    public function increasePosition()
    {
        $this->position++;
    }

    /**
     * {@inheritdoc}
     */
    public function decreasePosition()
    {
        $this->position--;
    }
}
```

**Note:** that `PositionableInterface` implementation is required

Sample datagrid definition:

```yml
columns:
  title:
    type: text
    options:
      display_order: 1
      label: "backend.promotions.datagrid.title.label"
  actions:
    type: action
    options:
      display_order: 2
      label: "backend.promotions.datagrid.actions.label"
      field_mapping: [ id ]
      actions:
        move_up:
          route_name: "fsi_admin_positionable_decrease_position"
          additional_parameters: { element: "promotions" }
          parameters_field_mapping: { id: id }
          content: <span class="glyphicon glyphicon-arrow-up icon-white"></span>
          url_attr: { class: "btn btn-warning btn-sm" }
        move_down:
          route_name: "fsi_admin_positionable_increase_position"
          additional_parameters: { element: "promotions" }
          parameters_field_mapping: { id: id }
          content: <span class="glyphicon glyphicon-arrow-down icon-white"></span>
          url_attr: { class: "btn btn-warning btn-sm" }
```
