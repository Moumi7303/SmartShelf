<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Member;
use App\Models\BookCopy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $issueDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $issueDate)->modify('+14 days');
        
        return [
            'transaction_code' => Transaction::generateTransactionCode(),
            'member_id' => Member::factory(),
            'book_copy_id' => BookCopy::factory(),
            'issued_by' => User::factory(),
            'returned_to' => null,
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'return_date' => null,
            'renewal_count' => $this->faker->numberBetween(0, 2),
            'status' => 'issued',
            'remarks' => $this->faker->optional()->sentence(),
        ];
    }
}
