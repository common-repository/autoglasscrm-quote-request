<?php

namespace QuoteHelpers;

class AddressLocalize
{
    const UPPERCASE_FIRST = 1;
    const UPPERCASE_ALL = 2;
    const UPPERCASE_NONE = 3;

    private $country;

    public function __construct($country = null)
    {
        if(!empty($country)) {
            $this->country = strtoupper($country);
        }
        else if(isset($_SESSION['country'])) {
            $this->country = $_SESSION['country'];
        }
        else {
            $this->country = 'US';
        }
    }

    public function addressText($cap = self::UPPERCASE_FIRST)
    {
        switch($this->country) {
            case 'ZA':
                $t = 'Street Address / Suburb';
                break;
            case 'NZ':
                $t = 'Street Address / Suburb / RD';
                break;
            default:
                $t = 'Street Address';
                break;
        }
        if($cap === self::UPPERCASE_FIRST) {
            return $t;
        } else if($cap === self::UPPERCASE_NONE) {
            return mb_strtolower($t);
        } else if($cap === self::UPPERCASE_ALL) {
            return mb_strtoupper($t);
        }
    }

    public function stateText($cap = self::UPPERCASE_FIRST)
    {
        switch($this->country) {
            case 'AU':
            case 'MX':
            case 'US':
                $t = 'State';
                break;
            case 'CA':
                $t = 'Province';
                break;
            case 'GB':
            case 'NZ':
            case 'ZA':
                $t = false;
                break;
            default:
                $t = 'State/Province';
                break;
        }
        if($cap === self::UPPERCASE_FIRST) {
            return $t;
        } else if($cap === self::UPPERCASE_NONE) {
            return mb_strtolower($t);
        } else if($cap === self::UPPERCASE_ALL) {
            return mb_strtoupper($t);
        }
    }

    public function zipText($cap = self::UPPERCASE_FIRST)
    {
        switch($this->country) {
            case 'US':
                $t = 'Zip Code';
                break;
            case 'AU':
            case 'CA':
            case 'GB':
            case 'MX':
            case 'NZ':
            case 'ZA':
                $t = 'Postal Code';
                break;
            default:
                $t = 'Postal Code';
                break;
        }
        if($cap === self::UPPERCASE_FIRST) {
            return $t;
        } else if($cap === self::UPPERCASE_NONE) {
            return mb_strtolower($t);
        } else if($cap === self::UPPERCASE_ALL) {
            return mb_strtoupper($t);
        }
    }

    // this country has been set up
    public function has()
    {
        return in_array($this->country, ['US', 'AU', 'CA', 'GB', 'MX', 'NZ', 'ZA']);
    }

    // this country has a state dropdown file
    public function hasStateDropdown()
    {
        file_exists(dirname(__FILE__) . '/states/' . $this->country . '.php');
    }

    /**
     * @return string HTML options for state dropdown select
     */
    public function stateDropdown($selectState = null)
    {
        require dirname(__FILE__) . '/states/' . $this->country . '.php';
    }

    public function supportsRegistrationPlate()
    {
        return $this->country === 'GB';
    }

    public function windshieldText()
    {
        $text = 'Windshield';

        if (in_array($this->country, ['GB', 'AU', 'ZA', 'NZ', 'KE'])) {
            $text = 'Windscreen';
        }

        return $text;
    }
}
