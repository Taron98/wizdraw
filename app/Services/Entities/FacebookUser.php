<?php

namespace Wizdraw\Services\Entities;

use Carbon\Carbon;
use Facebook\Authentication\AccessToken;
use Facebook\GraphNodes\Birthday;

/**
 * Class FacebookUser
 * @package Wizdraw\Services\Entities
 */
class FacebookUser extends AbstractEntity
{

    /** @var  string|null */
    private $accessToken;

    /** @var  int|null */
    private $expire;

    /** @var  string|null */
    protected $id;

    /** @var  string|null */
    protected $email;

    /** @var  string|null */
    protected $firstName;

    /** @var  string|null */
    protected $middleName;

    /** @var  string|null */
    protected $lastName;

    /** @var string|null */
    protected $gender;

    /** @var  int|null */
    protected $birthday;

    /**
     * @param AccessToken $accessToken
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken->getValue();
        $this->expire = $accessToken->getExpiresAt()->getTimestamp();
    }

    /**
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return int|null
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @param int $expire
     *
     * @return $this
     */
    public function setExpire(int $expire)
    {
        $this->expire = $expire;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     *
     * @return $this
     */
    public function setMiddleName(string $middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     *
     * @return $this
     */
    public function setBirthday(Birthday $birthday)
    {
        if ($birthday->hasDate()) {
            $this->birthday = Carbon::instance($birthday);
        }

        return $this;
    }

}