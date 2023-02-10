<?php

namespace Daliendev;

use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Http\Requests\NovaRequest;

class PermissionGroup extends BooleanGroup
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'permission-group';


    /**
     * Default crud permissions which are applied to resources.
     * @var array|string[]
     */
    private array $crud_permissions = [
        'can_create' => 'can create',
        'can_read' => 'can read',
        'can_update' => 'can update',
        'can_delete' => 'can delete',
        'can_force_delete' => 'can force delete',
    ];

    /**
     * The child fields.
     *
     * @var array
     */
    public array $fields = [];

    public function __construct(string $name, string $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
            'type' => 'PermissionGroup',
        ]);
    }

    /**
     * Set BooleanGroup based on the resource name
     * @param string $resource
     * @param array $customPermissions
     * @return PermissionGroup
     */
    public function on(string $resource, array $customPermissions = []): PermissionGroup
    {
        preg_match('/\b(\w+)$/', $resource, $matches);
        $resource_name = (count($matches)) ? $matches[0] : null;

        $permissions = array_merge($this->crud_permissions, $customPermissions);
        $options = $this->formatOptions($resource_name, $permissions);

        $booleanGroup = BooleanGroup::make($resource_name)->options($options);

        $this->fields[] = $booleanGroup;
        return $this->withMeta(['children' => $this->fields]);
    }

    /**
     * Custom permissions
     * @param string $custom_name
     * @param array $customPermissions
     * @return PermissionGroup
     */
    public function with(string $custom_name, array $customPermissions): PermissionGroup
    {
        $options = $this->formatOptions($custom_name, $customPermissions, false);
        $booleanGroup = BooleanGroup::make($custom_name)->options($options);

        $this->fields[] = $booleanGroup;
        return $this->withMeta(['children' => $this->fields]);
    }

    private function formatOptions(string $name, array $permissions, bool $displayName = true): array
    {
        $options = [];
        foreach ($permissions as $key => $permission) {
            $options[strtolower($key . '_' . $name)] = ($displayName)
                ? ucfirst($permission . ' ' . $name)
                : ucfirst($permission);
        }

        return $options;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param NovaRequest $request
     * @param string $requestAttribute
     * @param object $model
     * @param string $attribute
     * @return void
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute): void
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request[$requestAttribute], true);
        }
    }


}
