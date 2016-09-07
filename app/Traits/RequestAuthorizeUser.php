<?php

namespace Wizdraw\Traits;

/**
 * Class RequestAuthorizeUser
 * @package Wizdraw\Traits
 */
trait RequestAuthorizeUser
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return $this->user()->getId() == $this->route('id');
    }

}