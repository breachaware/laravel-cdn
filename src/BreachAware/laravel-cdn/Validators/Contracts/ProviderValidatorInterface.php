<?php

namespace BreachAware\LaravelCdn\Validators\Contracts;

/**
 * Interface ProviderValidatorInterface.
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface ProviderValidatorInterface
{
    public function validate($configuration, $required);
}
