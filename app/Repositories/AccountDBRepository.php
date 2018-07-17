<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan
 * Date: 7/10/2018
 * Time: 12:37 PM
 */

namespace App\Repositories;


use App\Account;

class AccountDBRepository implements AccountRepository
{

    public function getAll()
    {
        // TODO: Implement getAll() method.
        return Account::all();
    }
}