<?php

namespace App\Interfaces;

interface TransactionTypeInterface {
    const MONEY_TRANSFER = 'MONEY TRANSFER';
    const MONEY_WITHDRAWAL = 'MONEY WITHDRAWAL';
    const MONEY_DEPOSIT = 'MONEY DEPOSIT';
}